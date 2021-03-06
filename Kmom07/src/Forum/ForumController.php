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



    // All redirect links used.
    private $redirect = [
        'menu'          => 'Forum/menu/',
        'question'      => 'Forum/question/',
        'addQuestion'   => 'Forum/addQuestion/',
        'addAnswer'     => 'Forum/addAnswer/',
        'addComment'    => 'Forum/addComment/',
        'rateQuestion'  => 'Forum/vote/Q/',
        'rateAnswer'    => 'Forum/vote/A/',
        'rateComment'   => 'Forum/vote/C/',
        'accepted'      => 'Forum/accepted/',
        'tagButton'     => 'Forum/tag/',
        'addTag'        => 'Forum/addTag/',
        'profile'       => 'Users/profile/',
        "login"         => "Users/login"
    ];

    // All template links used.
    private $template = [
        "menu"          => "forum/forum-menu",
        "question"      => "forum/forum-question",
        "tagMenu"       => "forum/forum-tagMenu",
        "tagQuestion"   => "forum/forum-tagQuestion",
        "userSketch"    => "forum/forum-userSketch"
    ];



    /**
    * Initialize the controller.
    *
    * @return void
    */
    public function initialize()
    {
        $this->users = new \Anax\Users\User();
        $this->users->setDI($this->di);
        $this->di->session();

        $this->questions = new \Anax\Forum\Question();
        $this->questions->setDI($this->di);

        $this->answers = new \Anax\Forum\Answer();
        $this->answers->setDI($this->di);

        $this->comments = new \Anax\Forum\Comment();
        $this->comments->setDI($this->di);

        $this->tags = new \Anax\Forum\Tag();
        $this->tags->setDI($this->di);

        $this->linkQuestionToTags = new \Anax\Forum\linkQuestionToTags();
        $this->linkQuestionToTags->setDI($this->di);

        $this->linkQuestionToUserVotes = new \Anax\Forum\linkQuestionToUserVotes();
        $this->linkQuestionToUserVotes->setDI($this->di);

        $this->linkAnswerToUserVotes = new \Anax\Forum\linkAnswerToUserVotes();
        $this->linkAnswerToUserVotes->setDI($this->di);

        $this->linkCommentToUserVotes = new \Anax\Forum\linkCommentToUserVotes();
        $this->linkCommentToUserVotes->setDI($this->di);

        $this->time = new \Anax\Forum\CFormatUnixTime();

        $this->table = new \Anax\HTMLTable\HTMLTable();
    }



    //---------------- Menu actions ----------------
    /**
    * Displays all questions in the database or sorted by tag.
    *
    * @param, string, the unique tag name to sort by.
    *
    * @return void.
    */
    public function menuAction($tagName=null)
    {
        $tagName = urldecode($tagName);
        $result = array("default" => "value");

        if (empty($tagName)) {
            // Get all questions.
            $result = $this->time->formatUnixProperties($this->questions->findAll());
        } else {   // Check if the tag exists.
            if (!empty($this->tags->findByName($tagName))) {
                $result = $this->time->formatUnixProperties($this->linkQuestionToTags->selectByTag($this->tags->id));
            }
        }

        $this->dispatcher->forwardTo('Users', 'status');
        $this->dispatcher->forwardTo('Forum', 'tagMenu');

        $conditions = ['admin', $this->users->currentUser()];
        $this->theme->setTitle("All Questions");
        $this->views->add($this->template["menu"], [
            'admin'      => $this->users->isUserAdmin($this->users->currentUser(), $conditions),
            'questions'  => $result,
            'title'      => "All questions",
            'redirect'   => $this->redirect
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
        $result = $this->questions->findByUserId($id);

        if (!empty($result)) {
            $this->theme->setTitle("All Questions");
            $this->views->add($this->template["menu"], [
                'questions' => $this->time->formatUnixProperties($result),
                'title'     => "Questions asked by this user",
                'redirect'  => $this->redirect
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
    public function questionAction($id, $sort=null)
    {
        $this->theme->addJavascript("js/saveVoteScrollState.js");

        // Clean the $id and get the question.
        $question = $this->questions->find($id);

        // Save the question in session for easy access elsewhere.
        $this->questions->setQuestionId($question->id);
        $answers = array();
        $questionComments = array();
        $answerComments = array();

        // Check if the question did indeed exist.
        if (!empty($question)) {
            $question = $this->time->formatUnixProperty($question);

            // Get comments to the question (if any) and format timestamp.
            $questionComments = $this->comments->findQuestionComments($id);
            $questionComments = ($questionComments) ? $this->time->formatUnixProperties($questionComments) : [];

            switch ($sort) {
                case 'timestamp':
                    $answers = $this->answers->sortByTime($id);
                    break;
                case 'rating':
                    $answers = $this->answers->sortByRating($id);
                    break;
                default:
                    $answers = $this->answers->findByQuestionId($id);
            }

            if (!empty($answers)) {
                // Format the answers unix timestamps.
                $answers = $this->time->formatUnixProperties($answers);

                // For each answer, find the corresponding comments.
                foreach ($answers as $item) {
                    $comments = $this->comments->findAnswerComments($item->id);
                    $answerComments[$item->id] = $this->time->formatUnixProperties($comments);
                }
            }

            $condition = ['admin', $question->user];

            // Set the title of the browser tab.
            $this->theme->setTitle($question->title);
            $this->views->add($this->template["question"], [
                'admin'             => $this->users->isUserLoggedIn(),
                'questionAdmin'     => $this->users->isUserAdmin($this->users->currentUser(), $condition),
                'redirect'          => $this->redirect,
                'question'          => $question,
                'questionComments'  => $questionComments,
                'answers'           => $answers,
                'answerComments'    => $answerComments
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
        $questions = $this->questions->getRecentQuestions();
        $questions = (!empty($questions)) ? $this->time->formatUnixProperties($questions) : [];

        $this->views->add($this->template["menu"], [
            "title"      => "Recent Questions",
            "redirect"   => $this->redirect,
            "class"      => "width60",
            "questions"  => $questions
        ]);

        $this->views->add($this->template["userSketch"], [
            "title" => "Most active users",
            "redirect"   => $this->redirect,
            'users' => $this->users->getTopRatedUsers()
        ]);

        $this->views->add($this->template["tagMenu"], [
            "title" => "Popular tags",
            "class" => "clear",
            "tags"  => $this->tags->getPopularTags()
        ]);
    }



    //---------------- Score ----------------
    /**
    * Calculate the overall score of a user.
    *
    * @param string, the id of the user to calculate score for.
    *
    * @return void.
    */
    public function scoreAction($id)
    {
        if (is_numeric($id)) {
            // Calculate QAC score.
            $q = $this->questions->calculateScore($id);
            $a = $this->answers->calculateScore($id);
            $c = $this->comments->calculateScore($id);
            $totalScore = $q["sum"] + $a["sum"] + $c["sum"];

            // Create score table.
            $table = $this->table->createTable([
                "class" => "width45",
                ["", "Amount", "Rating", "Sum", "class" => "menu-table-header"],
                ["Q", $q["count"], $q["rating"], $q["sum"]],
                ["A", $a["count"], $a["rating"], $a["sum"]],
                ["C", $c["count"], $c["rating"], $c["sum"]]
            ]);
            $table .= "<br><br><br><br><br><p><b>User rating:</b> {$totalScore}</p>";

            // Set id of the row to update and update the rows score.
            $this->users->id = $id;
            $this->users->update([
                "score" => $totalScore
            ]);

            // Render form.
            $this->utility->renderDefaultPage("Rating", $table);
        }
    }



    //---------------- Tags ----------------
    /**
    * Sorting Menu of all the available tags.
    *
    * @return void.
    */
    public function tagMenuAction()
    {
        $this->views->add($this->template["tagMenu"], [
            'title'      => "Tags",
            'redirect'   => $this->redirect,
            'tags'       => $this->tags->findAll()
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
        $this->views->add($this->template["tagQuestion"], [
            'title'      => "Tags",
            'redirect'   => $this->redirect,
            'tags'       => $this->tags->findAll(),
            'questionid' => $this->questions->getQuestionId()
        ]);
    }



    //---------------- Ratings ----------------
    private function vote($model, $linktable, $rowId, $number)
    {
        if ($this->users->currentUser() == "admin") {
            $model->editVote($rowId, $number);
        } else {
            $userId = $this->users->findByAcronym($this->users->currentUser())->id;
            if ($linktable->userHasNotVoted($rowId, $userId)) {
                $model->editVote($rowId, (1 * $number));
                $linktable->addUserVote($rowId, $userId, $number);
            } elseif ($linktable->userHasVoted($rowId, $userId)) {
                $model->editVote($rowId, (-1 * $linktable->getVoteType($rowId, $userId)));
                $linktable->removeUserVote($rowId, $userId);
            }
        }
    }

    /**
    * Function to edit the rating of a question, answer or comment.
    *
    * @param string, a 1 letter value to determine which table to use.
    * @param string, the unique id of the row to use in the QAC table.
    * @param string, a positive or negative number to add to the rating score.
    *
    * @return void.
    */
    public function voteAction($table, $rowId, $number)
    {
        if ($this->users->isUserLoggedIn()) {
            // Find database table and change the rating of the row in that table.
            if (is_numeric($rowId) && ($number == 1 || $number == -1)) {
                switch ($table) {
                    case 'Q':
                        $this->vote($this->questions, $this->linkQuestionToUserVotes, $rowId, $number);
                        break;
                    case 'A':
                        $this->vote($this->answers, $this->linkAnswerToUserVotes, $rowId, $number);
                        break;
                    case 'C':
                        $this->vote($this->comments, $this->linkCommentToUserVotes, $rowId, $number);
                        break;
                }

                $this->utility->createRedirect($this->redirect["question"] . $this->questions->getQuestionId());
            } else {
                die("Error: invalid parameters in ForumController.voteAction().");
            }
        } else {
            $this->utility->createRedirect($this->redirect["login"]);
        }
    }


    // ---------------- Accept answer ---------------
    /**
    * Accepts or unaccepts an answer to a question as THE answer.
    *
    * @param string, treated as int, id of the answer to be accepted.
    *
    * @return void.
    */
    public function acceptedAction($id)
    {
        if (is_numeric($id)) {
            // Find row in db and simultaneously set $this->answer->id so the db knows which row to update.
            $this->answers->find($id);
            $this->answers->update([
                'accepted'  => ($this->answers->accepted == 0) ? 1 : 0
            ]);

            // Create redirect link using the questions id.
            $this->utility->createRedirect($this->redirect["question"] . $this->questions->getQuestionId());
        } else {
            die("Error: Invalid parameter in ForumController.acceptedAction() Id is not numeric.");
        }
    }



    //---------------- Questions, answers, comments and tags actions ----------------
    /**
    * Function that adds a new question to the database.
    *
    * @return void.
    */
    public function addQuestionAction()
    {
        $obj = new \Anax\Forum\CFormQuestionModel();

        $callable = function ($form, $scope) {
            $result = false;

            // Check if user exists and load into model if so.
            if (!empty($scope->users->findByAcronym($scope->users->currentUser()))) {
                $form->saveInSession = true;

                // Save form.
                $createResult = $scope->questions->create([
                    'user'      => $scope->users->acronym,
                    'userid'    => $scope->users->id,
                    'title'     => $form->Value('title'),
                    'content'   => $form->Value('content'),
                    'timestamp' => time(),
                    'rating'    => 0,
                    'answered'  => 0
                ]);

                ($createResult)
                    ? $result = true
                    : die("ForumController.addQuestionAction.callback: Question creation failed.");

                $scope->utility->createRedirect($scope->redirect["menu"]);
            }

            return $result;
        };

        // Render form.
        $this->utility->renderDefaultPage("Create Question", $obj->createQuestionForm($this, $callable));
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
        $formObj = new \Anax\Forum\CFormAnswerModel();

        $values = ['questionid' => $this->escaper->escapeHTMLattr($id)];

        $callable = function ($form, $scope) {
            $result = false;

            // Check if user exists and load into model if so.
            if (!empty($scope->users->findByAcronym($scope->users->currentUser()))) {
                // Save form.
                $createResult = $scope->answers->create([
                    'questionid'    => $form->Value('questionid'),
                    'user'          => $scope->users->acronym,
                    'userid'        => $scope->users->id,
                    'content'       => $form->Value('content'),
                    'timestamp'     => time(),
                    'rating'        => 0,
                    'accepted'      => 0
                ]);

                // Update the question and report that it has received another answer.
                $updateResult = $scope->questions->update([
                    'answered'  => $scope->questions->find($form->Value('questionid'))->answered + 1
                ]);

                ($createResult && $updateResult)
                    ? $result = true
                    : die("ForumController.addAnswerAction.callback: Creation or update of answer to question failed.");

                // Use the questionid to create a redirect link back to the question.
                $this->utility->createRedirect($scope->redirect["question"] . $form->Value('questionid'));
            }

            return $result;
        };

        // Render form.
        $this->utility->renderDefaultPage("Create Answer", $formObj->createAnswerForm($values, $this, $callable));
    }



    /**
    * Function that adds a new answer comment to the database.
    *
    * @param string, the ID of the question in the database.
    * @param string, the ID of the question or answer the comment belongs to.
    * @param string, a string indicating what the comments parent is.
    *
    * @return void.
    */
    public function addCommentAction($questionid, $qaid, $parent)
    {
        $formObj = new \Anax\Forum\CFormCommentModel();

        $values = [
            'questionid'      => $this->escaper->escapeHTMLattr($questionid),
            'qaid'            => $this->escaper->escapeHTMLattr($qaid),
            'commentparent'   => $this->escaper->escapeHTMLattr($parent)
        ];

        $callable = function ($form, $scope) {
            $result = false;

            // Check if user exists and load into model if so.
            if (!empty($scope->users->findByAcronym($scope->users->currentUser()))) {
                $form->saveInSession = true;
                // Save form.
                $createResult = $scope->comments->create([
                    'user'          => $scope->users->acronym,
                    'commentparent' => $form->Value('commentparent'),
                    'qaid'          => $form->Value('qaid'),
                    'userid'        => $scope->users->id,
                    'content'       => $form->Value('content'),
                    'timestamp'     => time(),
                    'rating'        => 0
                ]);

                ($createResult)
                    ? $result = true
                    : die("ForumController.addCommentAction.callback: Comment creation failed.");

                $scope->utility->createRedirect($scope->redirect["question"] . $form->Value('questionid'));
            }

            return $result;
        };

        // Render form.
        $this->utility->renderDefaultPage("Create Comment", $formObj->createCommentForm($values, $this, $callable));
    }



    /**
    * Render a create tag form and add the input to a question.
    *
    * @param string, name of the tag to add.
    *
    * @return void.
    */
    public function addTagAction($tag=null)
    {
        $obj = new \Anax\Forum\CFormTagModel();
        $values = !empty($tag) ? ['name' => $this->escaper->escapeHTMLattr($tag)] : [];

        $callback = function ($form, $scope) {
            $result = false;
            $questionId = $scope->questions->getQuestionId();
            $tagName = $form->Value('name');

            // Check if question exists.
            if ($scope->questions->find($questionId)) {
                // If the question exists, check or create the tag.
                if (empty($scope->tags->findByName($tagName))) {
                    $scope->tags->create([
                        'name' => $tagName
                    ]);
                }

                if (!$scope->linkQuestionToTags->questionHasTag($questionId, $scope->tags->id)) {   // If the question doesn't have tag, apply it:
                    $form->saveInSession = true;

                    // Create a row that links the question to the tag.
                    ($scope->linkQuestionToTags->createLink($questionId, $scope->tags->id))
                        ? $result = true
                        : die("ForumController.addCommentAction.callback: Comment creation failed.");
                }

                // Use questionId to create a redirect link back to that question.
                $scope->utility->createRedirect($scope->redirect["question"] . $questionId);
            }

            return $result;
        };

        $this->utility->renderDefaultPage("Create Tag", $obj->createTagForm($values, $this, $callback));
    }
}
