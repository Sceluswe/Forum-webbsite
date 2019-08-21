<?php
namespace Anax\Forum;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class ForumController implements \Anax\DI\IInjectionAware
{
	use \Anax\DI\TInjectable,
		\Anax\MVC\TRedirectHelpers;

	/**
	* Initialize the controller.
	*
	* @return void
	*/
	public function initialize()
	{
		$this->questions = new \Anax\Forum\Question();
		$this->questions->setDI($this->di);

		$this->answers = new \Anax\Forum\Answer();
		$this->answers->setDI($this->di);

		$this->comments = new \Anax\Forum\Comment();
		$this->comments->setDI($this->di);

		$this->users = new \Anax\Users\User();
		$this->users->setDI($this->di);
		$this->di->session();

		$this->tags = new \Anax\Forum\Tag();
		$this->tags->setDI($this->di);

        $this->time = new \Anax\Forum\CFormatUnixTime();

        $this->table = new \Anax\HTMLTable\HTMLTable();
	}



	/**
	* Return redirects.
	*
	* @return array containing redirects.
	*/
	public function redirects()
	{
		return $values = [
			'menu'			=> 'Forum/menu/',
			'addQuestion' 	=> 'Forum/addQuestion/',
			'addAnswer'	  	=> 'Forum/addAnswer/',
            'addComment' 	=> 'Forum/addComment/',
			'rateQuestion'	=> 'Forum/vote/Q/',
			'rateAnswer'	=> 'Forum/vote/A/',
			'rateComment'	=> 'Forum/vote/C/',
			'user' 			=> 'Users/id/',
			'question' 		=> 'Forum/id/',
			'accepted'		=> 'Forum/accepted/',
			'tagButton'		=> 'Forum/tag/',
			'tagCreate'		=> 'Forum/tagCreate/',
		];
	}



    /**
    * Displays the currently logged in user and links to its profile.
    *
    * @return void.
    */
	public function userStatusAction()
	{
		$userlink = "You are currently not logged in. <a href='" . $this->url->create("Users/Login") . "'>Login</a>";

		if($this->users->isUserLoggedIn())
		{
			$user = $this->users->findByColumn('acronym', $this->users->currentUser());

            // Create a link to the currently logged in user.
			$userlink = "You are currently logged in as: <a href='"
				. $this->url->create("Users/id/{$user[0]->id}")
				. "'>" . ucfirst($user[0]->acronym) . "</a>";
		}

		// Render form.
        $this->utility->renderDefaultPage("", $userlink);
	}



//---------------- Menu actions ----------------
	/**
	* Function that displays all questions on the database or by sorted value.
	*
	* @param, string, the tag name to sort by.
    *
    * @return void.
	*/
	public function menuAction($tagName=null)
	{
		$result = array();
		if(!empty($tagName))
		{
			$res = $this->tags->findByColumn('tag', $tagName);

			// Check if there are any questions with this tag.
			if(!empty($res))
			{
				foreach($res as $key => $item)
				{
					$this->questions->query()->where('id = ?');
                    $question = $this->questions->execute([$item->questionid])[0];
                    $result[] = $this->time->formatUnixProperty($question);
				}
			}
		}
        else
		{	// Get all questions.
			$result = $this->time->formatUnixProperties($this->questions->findAll());
		}

        $this->dispatcher->forwardTo('Forum', 'userStatus');
        $this->dispatcher->forwardTo('Forum', 'tagMenu');

        $conditions = ['admin', $this->users->currentUser()];
		$this->theme->setTitle("All Questions");
		$this->views->add('forum/forum-menu', [
			'admin'		=> $this->users->isUserAdmin($this->users->currentUser(), $conditions),
			'questions' => $result,
			'title' 	=> "All questions",
			'redirect' 	=> $this->redirects(),
		]);
	}



	/**
	* Function that displays all questions posted by the user with the parameterized id.
	*
	* @param The database id of the user.
    *
    * @return void.
	*/
	public function userQuestionsAction($id)
	{
		$result = $this->questions->findByColumn('userid', htmlentities($id));

		if(!empty($result))
		{
			$this->theme->setTitle("All Questions");
			$this->views->add('forum/forum-menu', [
				'questions' => $this->time->formatUnixProperties($result),
				'title' => "Questions asked by this user",
				'redirect' => $this->redirects()
			]);
		}
	}



	/**
	* Displays one question and all answers and comments that belong to it.
	*
    * @param int, the id of the question to display.
    * @param string, the column to sort after.
    *
    * @return void.
	*/
	public function idAction($id, $sort=null)
	{
		// Clean the $id and get the question.
		$question = $this->questions->find(htmlentities($id));

        // Save the question in session for easy access elsewhere.
		$this->questions->setQuestion($question->id);
        $answers = array();
        $questionComments = array();
        $answerComments = array();

		// Check if the question did indeed exist.
		if(!empty($question))
		{
			$question = $this->time->formatUnixProperty($question);

            // If question is not empty, get the comments to that question.
			$questionComments = $this->comments->findQuestionComments($id);

			// Check if the question has any comments.
			$questionComments = ($questionComments) ? $this->time->formatUnixProperties($questionComments) : array();

            switch ($sort)
            {
                case 'timestamp':
                    $this->answers->query()->where('questionid= ?')->orderBy('timestamp DESC');
                    $answers = $this->answers->execute([$id]);
                    break;
                case 'rating':
                    $this->answers->query()->where('questionid= ?')->orderBy('rating DESC');
                    $answers = $this->answers->execute([$id]);
                    break;
                default:
                    $answers = $this->answers->findByColumn('questionid', $id);
            }

			if(!empty($answers))
			{
                // Format the answers unix timestamps.
                $answers = $this->time->formatUnixProperties($answers);

                // For each answer, find the corresponding comments.
				foreach($answers as $item)
				{
                    $comments = $this->comments->findAnswerComments($item->id);
					$answerComments[$item->id] = $this->time->formatUnixProperties($comments);
				}
			}

			$condition = ['admin', $question->user];

            // Set the title of the browser tab.
			$this->theme->setTitle($question->title);
			$this->views->add('forum/forum-question', [
				'admin'				=> $this->users->isUserLoggedIn(),
				'questionAdmin'		=> $this->users->isUserAdmin($this->users->currentUser(), $condition),
				'redirect' 			=> $this->redirects(),
				'question'  		=> $question,
				'questionComments'  => $questionComments,
				'answers'		 	=> $answers,
				'answerComments' 	=> $answerComments
			]);
		}
	}



	/**
	* Display the homepage.
    *
    * @return void.
	*/
	public function homeAction()
	{
		// Get the recently posted questions.
		$this->questions->query()->orderBy('timestamp DESC LIMIT 6');
		$questions = $this->questions->execute();
        $questions = (!empty($questions)) ? $this->time->formatUnixProperties($questions) : array();

		// Get the most popular tags.
		$this->tags->query('tag, COUNT(1) AS num')->groupBy('tag')->orderBy('num DESC LIMIT 12');
		$tags = $this->tags->execute();

		// Get the highest rated users.
		$this->users->query()->orderBy('score DESC LIMIT 6');
		$users = $this->users->execute();

		$this->views->add('forum/forum-home', [
			'questions' => $questions,
			'title1' => "Recent Questions",
			'redirect' => $this->redirects(),
			'title2' => "Most active users",
			'users' => $users,
			'title3' => "Popular tags",
			'tags' => $tags,
		]);
	}

//---------------- Score ----------------
    /**
	* Calculate the overall score of a user.
    *
    * @param int, the id of the user to calculate score for.
    *
    * @return void.
	*/
	public function scoreAction($id)
	{
		//Get id from current user.
		$userid = htmlentities($id);

		$questions = $this->questions->findByColumn('userid', $userid);
		$answers = $this->answers->findByColumn('userid', $userid);
		$comments = $this->comments->findByColumn('userid', $userid);

        // Calculate QAC score.
		$qRating = $this->utility->ratingSum($questions);
		$aRating = $this->utility->ratingSum($answers);
		$cRating = $this->utility->ratingSum($comments);

		$nrOfQ = count($questions);
		$nrOfA = count($answers);
		$nrOfC = count($comments);
		$sumQ = $qRating + $nrOfQ;
		$sumA = $aRating + $nrOfA;
		$totalScore = $sumQ + $sumA + $nrOfC;

        // Create score table.
        $table = $this->table->createTable([
            'class' => 'width45',
            ['', 'Amount', 'Rating', 'Sum', 'class' => 'menu-table-header'],
            ['Q', $nrOfQ, $qRating, $sumQ],
            ['A', $nrOfA, $aRating, $sumA],
            ['C', $nrOfC, $cRating, $nrOfC]
        ]);
        $table .= "<br><br><br><br><br><p><b>User rating:</b> {$totalScore}</p>";

		// Update the users score.
		$this->users->id = $id;
		$this->users->update([
			'score'	=> $totalScore
		]);

		// Render form.
        $this->utility->renderDefaultPage("Rating", $table);
	}

//---------------- Tags ----------------
	/**
	* Sorting Menu of all the available tags.
    *
    * @return void.
	*/
	public function tagMenuAction()
	{
		$this->views->add('forum/forum-tagMenu', [
			'title'		=> "Tags",
			'redirect' 	=> $this->redirects(),
			'tags'		=> $this->tags->query('DISTINCT tag')->execute(),
		]);
	}



	/**
	* Displays all existing question tags and a 'create tag' button.
    *
    * @return void.
	*/
	public function tagAction()
	{
		// Create a menu with all unique tags that can be applied to the question.
		$this->theme->setTitle("Tag a question");
		$this->views->add('forum/forum-tagQuestion', [
			'title'		=> "Tags",
			'redirect' 	=> $this->redirects(),
			'tags'		=> $this->tags->query('DISTINCT tag')->execute(),
			'questionid'=> $this->questions->getQuestion(),
		]);
	}



	/**
	* Render a create tag form and add the input to a question.
    *
    * @param string, name of the tag to add.
    *
    * @return void.
	*/
	public function tagCreateAction($tag=null)
	{
		$values = (!empty($tag))
			? $values = ['tag' => $this->escaper->escapeHTML($tag)]
            : [];

		// Render form.
        $this->utility->renderDefaultPage("Create Tag", $this->getTagForm($values));
	}



	/**
	* Create a form for creating a tag.
	*
    * @param array, contains the name of the tag to create.
    *
	* @return the HTML code of the form.
	*/
	public function getTagForm(array $values)
	{
		// Initiate object instance.
		$form = new \Mos\HTMLForm\CForm();

		// Create tag form.
		$form = $form->create([], [
			'tag' => [
				'type' 		=> !empty($values) ? 'hidden' : 'text',
				'required' 	=> true,
				'validation'=> ['not_empty'],
				'value' 	=> !empty($values) ? $values['tag'] : '',
			],
			'submit' => [
				'type' 		=> 'submit',
				'class' 	=> 'cform-submit',
				'callback'  => [$this, 'callbackCreateTag'],
				'value'		=> !empty($values) ? "Add tag: {$values['tag']}" : "Create tag"
			]
		]);

		// Check the status of the form.
		$form->check([$this, 'callbackSuccess'], [$this, 'callbackFail']);

		return $form->getHTML();
	}



	/**
    * Callback for createTag success.
    *
    * @param object, CForm object containing user inut from the create tag form.
    *
    * @return boolean, true if tag creation is successful.
    */
	public function callbackCreateTag($form)
    {
		$result = false;
		// Clean the variables.
		$id = htmlentities($this->questions->getQuestion());
		$tag = htmlentities($form->Value('tag'));

		// Check if question exists.
		$questionCheck = $this->questions->find($id);

		if(isset($questionCheck))
		{	// If question exists, check if tag already exists for that question.
			$this->tags->query()->where('questionid = ?')->andWhere('tag = ?');
			$checkTag = $this->tags->execute([$id, $tag]);

			if(empty($checkTag))
			{	// If tag does not exist.
				$form->saveInSession = true;

                // Create a tag to that question.
				$this->tags->create([
					'tag'		=> $tag,
					'questionid'=> $id,
				]);

				$result = true;
			}

			// Use the previous questionid to create a redirect link back to that question.
            $this->utility->createRedirect("Forum/id/" . $id);
		}

        return $result;
    }



//---------------- Ratings ----------------
    /**
    * Function finds the correct dataobject and updates its rating.
    *
    * @param object, the data in which the targeted dataobject exists.
    * @param string, the unique id of the row to use in the table/data.
    * @param string, a positive or negative number to add to the rating score.
    *
    * @return void.
    */
    private function editVote(object $data, $id, $number)
    {
        // Update it with an increase of 1.
        $data->update([
            'rating'=> $data->find($id)->rating + $number,
        ]);
    }



	/**
	* Function to edit the rating of a question, answer or comment.
	*
	* @param string, a 1 letter value to determine which table to use.
	* @param string, the unique id of the row to use in the table.
    * @param string, a positive or negative number to add to the rating score.
    *
    * @return void.
	*/
	public function voteAction($table, $rowid, $number)
	{
		if($this->users->isUserLoggedIn())
		{
            // Use the two parameters to find the correct database table
            // and change the rating of the row in that table.
            if(is_numeric($rowid) && ($number == 1 || $number == -1))
            {
                switch ($table)
                {
                    case 'Q':
                        $this->editVote($this->questions, $rowid, $number);
                        break;
                    case 'A':
                        $this->editVote($this->answers, $rowid, $number);
                        break;
                    case 'C':
                        $this->editVote($this->comments, $rowid, $number);
                        break;
                }

                $this->utility->createRedirect("Forum/id/" . $this->questions->getQuestion());
            }
            else
            {
                die("Error, invalid parameters.");
            }
		}
		else
		{
            $this->utility->createRedirect("Users/Login");
		}
	}



// ---------------- Accept answer ---------------
    /**
    * Accepts an answer to a question as THE answer.
    *
    * @param int, id of the answer to be accepted.
    *
    * @return void.
    */
	public function acceptedAction($id)
	{
		$answerid = htmlentities($id);
		if(is_numeric($answerid))
		{
			// Save the id in the module Answer.
			$this->answers->id = $answerid;

            // Update answer.
			$this->answers->update([
				'id' => $answerid,
				'accepted' => 1
			]);

            // Get the questions id.
			$qid = $this->questions->getQuestion();

            // Create redirect link using the questions id.
            $this->utility->createRedirect("Forum/Id/{$qid}");
		}
		else
		{
			die("Error: Id is not numeric.");
		}
	}



//---------------- Questions, answers and comments actions ----------------
	/**
	* Function that adds a new question to the database.
    *
    * @return void.
	*/
	public function addQuestionAction()
	{
		// Render form.
        $this->utility->renderDefaultPage("Create Question", $this->getQuestionForm());
	}



	/**
	* Function that adds a new answer to the database.
	*
	* @param, int, ID of the row in the database table.
    *
    * @return void.
	*/
	public function addAnswerAction($id)
	{
		// Render form.
        $this->utility->renderDefaultPage("Create Answer", $this->getAnswerForm(['questionid' => $id,]));
	}



    /**
	* Function that adds a new answer comment to the database.
	*
	* @param int, the ID of the question in the database.
    * @param int, the ID of the question or answer the comment belongs to.
    * @param string, a string indicating what the comments parent is.
    *
    * @return void.
	*/
	public function addCommentAction($questionid, $qaid, $parent)
	{
		$values = [
    		'questionid'	=>	$questionid,
    		'qaid'			=>	$qaid,
    		'commentparent' =>  $parent
		];

        // Render form.
        $this->utility->renderDefaultPage("Create Comment", $this->getCommentForm($values));
	}



	/*
	* Get a form for creating a answer.
	*
    * @param array, containing the question ID.
    *
	* @return the HTML code of the form.
	*/
	public function getAnswerForm(array $values)
	{
		// Initiate object instance.
		$form = new \Mos\HTMLForm\CForm();

		// Create answer form.
		$form = $form->create([], [
			'questionid' => [
				'type' 		=> 'hidden',
				'required' 	=> true,
				'validation'=> ['not_empty'],
				'value' 	=> $values['questionid'],
			],
			'content' => [
				'type'       => 'textarea',
				'required'   => true,
				'class' 	 => 'cform-textarea',
				'validation' => ['not_empty'],
				'value' 	 => ''
			],
			'submit' => [
				'type' 		=> 'submit',
				'class' 	=> 'cform-submit',
				'callback'  => [$this, 'callbackCreateAnswer'],
				'value'		=> 'Post answer'
			]
		]);

		// Check the status of the form
		$form->check([$this, 'callbackSuccess'], [$this, 'callbackFail']);

		return $form->getHTML();
	}



	/**
    * Callback for createAnswer success.
    *
    * @param object, CForm object containing user input from the answer form.
    *
    * @return boolean, true if answer was created.
    */
	public function callbackCreateAnswer($form)
    {
		$result = false;
        // Get the current user from the database.
		$user = $this->users->findByColumn('acronym', $this->users->currentUser());

		if(!empty($user))
		{
			$form->saveInSession = true;

            // Save form.
			$this->answers->create([
				'questionid'=> $form->Value('questionid'),
				'user' 		=> $user[0]->acronym,
				'userid' 	=> $user[0]->id,
				'content' 	=> $form->Value('content'),
				'timestamp' => time(),
				'rating'	=> 0,
				'accepted'	=> 0,
			]);

			//Update the question and report that it has received another answer.
			$question = $this->questions->find($form->Value('questionid'));
			$this->questions->update([
				'id' => $question->id,
				'answered' => $question->answered + 1,
			]);

			$result = true;
			// Use the questionid to create a redirect link back to the question.
            $this->utility->createRedirect("Forum/id/" . $form->Value('questionid'));
		}

        return $result;
    }



	/*
	* Get a form for creating a question.
	*
    * @param array, containing data for the db.
    *
	* @return the HTML code of the form.
	*/
	public function getCommentForm(array $values)
	{
		// Initiate object instance.
		$form = new \Mos\HTMLForm\CForm();

		// Create form.
		$form = $form->create([], [
			'questionid' => [
				'type' 		=> 'hidden',
				'required' 	=> true,
				'validation'=> ['not_empty'],
				'value' 	=> $values['questionid'],
			],
			'qaid' => [
				'type' 		=> 'hidden',
				'required' 	=> true,
				'validation'=> ['not_empty'],
				'value' 	=> $values['qaid'],
			],
			'commentparent' => [
				'type' 		=> 'hidden',
				'required' 	=> true,
				'validation'=> ['not_empty'],
				'value' 	=> $values['commentparent'],
			],
			'content' => [
				'type'       => 'textarea',
				'required'   => true,
				'class' 	 => 'cform-textarea',
				'validation' => ['not_empty'],
				'value' 	 => ''
			],
			'submit' => [
				'type' 		=> 'submit',
				'class' 	=> 'cform-submit',
				'callback'  => [$this, 'callbackCreateComment'],
				'value'		=> 'Post comment'
			]
		]);

		// Check the status of the form
		$form->check([$this, 'callbackSuccess'], [$this, 'callbackFail']);

		return $form->getHTML();
	}



	/**
    * Callback for createComment success.
    *
    * @param object, CForm containing form input from the user.
    *
    * @return boolean, true if comment was created.
    */
	public function callbackCreateComment($form)
    {
		$result = false;
		// Get the current user from the database.
		$user = $this->users->findByColumn('acronym', $this->users->currentUser());

		if(!empty($user))
		{
			$form->saveInSession = true;
			// Save form.
			$this->comments->create([
				'user' 		=> $user[0]->acronym,
				'commentparent'	=> $form->Value('commentparent'),
				'qaid'		=> $form->Value('qaid'),
				'userid' 	=> $user[0]->id,
				'content' 	=> $form->Value('content'),
				'timestamp' => time(),
				'rating'	=> 0,
			]);

			$result = true;
            $this->utility->createRedirect("Forum/id/" . $form->Value('questionid'));
		}

        return $result;
    }



	/**
	* Get a form for creating a question
	*
	* @return the HTML code of the form.
	*/
	public function getQuestionForm()
	{
		// Initiate object instance.
		$form = new \Mos\HTMLForm\CForm();

		// Create form.
		$form = $form->create([], [
			'title' => [
				'type' 		=> 'text',
				'required' 	=> true,
				'class' 	=> 'cform-textbox',
				'validation'=> ['not_empty'],
				'value' 	=> ''
			],
			'content' => [
				'type'       => 'textarea',
				'required'   => true,
				'class' 	 => 'cform-textarea',
				'validation' => ['not_empty'],
				'value' 	 => ''
			],
			'submit' => [
    			'type' 		=> 'submit',
    			'class' 	=> 'cform-submit',
    			'callback'  => [$this, 'callbackCreateQuestion'],
    			'value'		=> 'Post question'
			]
		]);

		// Check the status of the form.
		$form->check([$this, 'callbackSuccess'], [$this, 'callbackFail']);

		return $form->getHTML();
	}



	/**
    * Callback for createQuestion success.
    *
    * @param object, CForm containing form input from the user.
    *
    * @return boolean, true if comment was created.
    */
	public function callbackCreateQuestion($form)
    {
		$result = false;
		// Get the current user from the database.
		$user = $this->users->findByColumn('acronym', $this->users->currentUser());

		if(!empty($user))
		{
			$form->saveInSession = true;

            // Save form.
			$this->questions->create([
				'user' 		=> $user[0]->acronym,
				'userid' 	=> $user[0]->id,
				'title' 	=> $form->Value('title'),
				'content' 	=> $form->Value('content'),
				'timestamp' => time(),
				'rating'	=> 0,
				'answered' 	=> 0,
			]);

			$result = true;
            $this->utility->createRedirect('Questions');
		}

        return $result;
    }



	/**
    * Callback for submit-button.
    *
    * @param object, CForm containing form input from the user.
    *
    * @return bool.
    */
    public function callbackSuccess($form)
    {
        $form->AddOutput("<p><i>Posted.</i></p>");
        return false;
    }



    /**
    * Callback for submit-button.
    *
    * @param object, CForm containing form input from the user.
    *
    * @return bool.
    */
    public function callbackFail($form)
    {
        $form->AddOutput("<p><i>DoSubmitFail(): Form was submitted but it failed to process/save/validate it</i></p>");
        return false;
    }
}
