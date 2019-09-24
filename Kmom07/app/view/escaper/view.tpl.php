<h1><?=$title?></h1>

<p>The entire package has been created based on the recommendations found here:
<a href="https://wiki.php.net/rfc/escaper">php.net</a></p>
<p>The entire package on GitHub: <a href="https://github.com/Sceluswe/XSS-Escaper">Escaper on GitHub</a></p>
<!-- ---------------- Step 1 ---------------- -->
<h2>Step 1: Download scelusswe/escaper</h2>
<p>Download the escaper package from packagist using composer by adding this line to your composer.json file:</p>
<pre><code>"require": {
  "scelusswe/escaper": "1.0"
}
</code></pre>


<!-- ---------------- Step 2 ---------------- -->
<h2>Step 2: Add as a service in Anax</h2>
<p>Add CEscaper as a service in CDIFactoryDefault.php:</p>
<pre><code>$this->setShared('escaper', function() {
		$escaper = new \Scelus\Escaper\CEscaper();
		return $escaper;
});</code></pre>



<!-- ---------------- Step 3---------------- -->
<h2>Step 3: Use it in your Anax controllers/frontcontrollers:</h2>
<!-- ---------------- HTML ---------------- -->
<h3>For data put into HTML:</h3>
<pre><code>$maliciousHTML = <?="{$HTML['original']}"?>


$this->escaper->escapeHTML($maliciousHTML);

Result: &<span>gt;&l</span>t;&#<span>x2F;div&</span>gt;&l<span>t;h1&</span>gt;myattack&l<span>t;&#x</span>2F;h1&<span>gt;</span></code></pre>


<!-- ---------------- HTML Attributes ---------------- -->
<h3>For data put into simple HTML attributes excluding 'href' and 'url':</h3>
<pre><code>$maliciousHTMLattr = <?=$HTMLattr['original']?>


$this->escaper->escapeHTMLattr($maliciousHTMLattr);

Result: &#<span>x22;&#</span>x3e;&#<span>x3c;h1&#</span>x3e;Hello&#<span>x3c;&#</span>x2f;table</code></pre>


<!-- ---------------- URLS ---------------- -->
<h3>For data put into HTML attributes: 'href' and 'url':</h3>
<p><pre><code>$maliciousURLattr = <?=$URL['original']?>


$this->escaper->escapeURL($maliciousURLattr);

Result: <?=$URL['result']?></code></pre></p>


<!-- ---------------- CSS ---------------- -->
<h3>For data put into CSS:</h3>
<p><pre><code>$maliciousCSS = <?=$CSS['original']?>


$this->escaper->escapeCSS($maliciousCSS);

Result: <?=$CSS['result']?></code></pre></p>


<!-- ---------------- Javascript ---------------- -->
<h3>For data put into Javascript:</h3>
<p><pre><code>$maliciousJS = <?=$JS['original']?>


$this->escaper->escapeJS($maliciousJS);

Result: <?=$JS['result']?></code></pre></p>
