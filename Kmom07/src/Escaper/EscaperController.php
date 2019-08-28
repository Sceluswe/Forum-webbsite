<?php
namespace Anax\Escaper;

/**
 * A controller for testing the escaper class.
 *
 */
class EscaperController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable,
		\Anax\MVC\TRedirectHelpers;

	/**
	* Add an options menu as view.
	*
	* @return void
	*/
	public function menuAction()
	{
		// Document title with malicious extra HTML tags
		$maliciousHTML = '></div><h1>myattack</h1>';

		// Malicious CSS class name
		$maliciousHTMLattr = '"><h1>Hello</table';

		// Malicious CSS class name
		$maliciousURL = '"><script>alert(1)</script><a href="#';

		// Malicious CSS font name
		$maliciousCSS = '"><script>alert(1)</script><a href="#';

		// Malicious Javascript text
		$maliciousJS = "'; alert(100); var x='";

		$HTMLresult = $this->escaper->escapeHTML($maliciousHTML);
		$HTMLresultattr = $this->escaper->escapeHTMLattr($maliciousHTMLattr);
		$URLresult = $this->escaper->escapeURL($maliciousURL);
		$CSSresult = $this->escaper->escapeCSS($maliciousCSS);
		$JSresult = $this->escaper->escapeJS($maliciousJS);

		$this->theme->setTitle("How to use: CEscaper");
		$this->views->add('escaper/view', [
			'title'      => 'How to use: CEscaper',
			'HTML'       => ['original' => $HTMLresult, 'result' => $HTMLresult],
			'HTMLattr'   => ['original' => $HTMLresultattr, 'result' => $HTMLresultattr],
			'URL'        => ['original' => $this->escaper->escapeHTML($maliciousURL), 'result' => $URLresult],
			'CSS'        => ['original' => $this->escaper->escapeHTML($maliciousCSS), 'result' => $CSSresult],
			'JS'         => ['original' => $this->escaper->escapeHTML($maliciousJS), 'result' => $JSresult]
		]);
	}
}
