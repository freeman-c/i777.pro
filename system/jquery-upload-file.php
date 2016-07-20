<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>jQuery Upload File Plugin Demo</title>
    <meta name="description" content="jQuery Upload File Plugin Demo- How to upload Multiple Files asynchronously(using jQuery Ajax) with progressbar">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta property='og:locale' content='en_US'/>    
	<link rel="author" href="https://plus.google.com/+RavishankerKusuma"/>    
    <link href="https://s3.amazonaws.com/hayageek/libs/jquery/bootstrap.min.css" rel="stylesheet">
    <link href="uploadfile.min.css" rel="stylesheet">
    <style>
    .highlight {
  display: none; /* hidden by default, until >480px */
  padding: 9px 14px;
  margin-bottom: 14px;
  background-color: #f7f7f9;
  border: 1px solid #e1e1e8;
  border-radius: 4px;
}
.highlight pre {
  padding: 0;
  margin-top: 0;
  margin-bottom: 0;
  background-color: transparent;
  border: 0;
  white-space: nowrap;
}
.highlight pre code {
  font-size: inherit;
  color: #333; /* Effectively the base text color */
}
.highlight pre .lineno {
  display: inline-block;
  width: 22px;
  padding-right: 5px;
  margin-right: 10px;
  text-align: right;
  color: #bebec5;
}

/* Show code snippets when we have the space */
@media screen and (min-width: 481px) {
  .highlight {
    display: block;
  }
}
</style>
<script type="text/javascript">
(function(){
  var bsa = document.createElement('script');
     bsa.type = 'text/javascript';
     bsa.async = true;
     bsa.src = 'http://s3.buysellads.com/ac/bsa.js';
  (document.getElementsByTagName('head')[0]||document.getElementsByTagName('body')[0]).appendChild(bsa);
})();
</script>
<script async src="http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
  </head>

  <body>
 <div class="navbar navbar-fixed-top">
   <div class="navbar-inner">
     <div class="container">
       <a rel="tooltip" title="Github - jQuery Upload File" class="brand" target="__blank" href="https://github.com/hayageek/jquery-upload-file/">Github</a>
       <a rel="tooltip" title="Download Source &amp; Examples" class="brand" target="__blank" href="http://hayageek.com/examples/jquery/jquery-multiple-file-upload/zips/jQuery-File-Upload.zip">Download</a>       
       <div class="nav-collapse collapse" id="main-menu">
        <ul class="nav" id="main-menu-left">
          <li><a rel="tooltip" href="http://hayageek.com" title="Hayageek.com Home Page">Home</a></li>
        </ul>
        <ul class="nav pull-right" id="main-menu-right" >
        <li style="margin-top:15px;margin-right:5px;"><form id="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post"> 
 <input type="hidden" name="cmd" value="_xclick"> 
 <input type="hidden" name="business" value="rskusuma@yahoo.com"> 
 <input type="hidden" name="item_name" value="Support Hayageek.com"> 
 <input type="hidden" name="buyer_credit_promo_code" value=""> 
 <input type="hidden" name="buyer_credit_product_category" value=""> 
 <input type="hidden" name="buyer_credit_shipping_method" value=""> 
 <input type="hidden" name="buyer_credit_user_address_change" value=""> 
 <input type="hidden" name="no_shipping" value="0"> 
 <input type="hidden" name="no_note" value="1"> 
 <input type="hidden" name="currency_code" value="USD"> 
 <input type="hidden" name="tax" value="0"> 
 <input type="hidden" name="lc" value="US"> 
 <input type="hidden" name="bn" value="PP-DonationsBF"> 
 <div><input id="butt" type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!"> </div>
</form>	</li>
        <li style="margin-top:15px;margin-right:5px;"><div data-href="http://hayageek.com/docs/jquery-upload-file.php" class="fb-like" data-layout="button_count" data-send="false" data-show-faces="false" data-width="120"></div></li>
        <li  style="margin-top:15px;"><a data-url="http://hayageek.com/docs/jquery-upload-file.php" href="https://twitter.com/share" class="twitter-share-button" data-count="horizontal"></a></li>
        <li style="margin-top:15px;"><div data-href="http://hayageek.com/docs/jquery-upload-file.php" class="g-plusone" data-annotation="inline" data-size="medium" data-width="120"></div></li>
        <form class="navbar-search pull-left" method="GET" action="http://hayageek.com/search.php">
            <input type="text" size="30" class="search-query" placeholder="Search" name="q" />
          </form>
 
        </ul>
 
       </div>
     </div>
   </div>
 </div>
 
  <div class="container">
<br/><br/>
<section id="typography">
  <div class="page-header">
    <h2>jQuery Upload File Plugin Demo</h2>
  </div>
<div style="width:728px;height:95px;">
<div id="bsap_1289820" class="bsarocks bsap_19097822778565d7c7d1cc1bcb8feb6a"></div>
</div>

  <!-- Headings & Paragraph Copy -->
  <div class="row">
  
  <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                  <li class="active"><a href="#start" data-toggle="tab">Getting Started</a></li>
                <li><a href="#single" title="Single File Upload" data-toggle="tab">Single File Upload</a></li>
                <li><a href="#multi" title="Multiple File Upload" data-toggle="tab">Multiple File</a></li>
                <li><a href="#advanced" title="Advanced File Upload" data-toggle="tab">Advanced</a></li>
                <li ><a href="#events" title="Events File Upload" data-toggle="tab">Events</a></li>
                <li><a href="#others" data-toggle="tab">Styling</a></li>
                <li><a href="#doc" data-toggle="tab">API &amp; Options</a></li>
                <li><a href="#server" data-toggle="tab">Server Side</a></li>
                
              </ul>
              <div id="tabcontent" class="tab-content">
<div class="tab-pane fade active in" id="start">
<p>
jQuery File UPload plugin provides Multiple file uploads with progress bar.
jQuery File Upload Plugin depends on <a href="http://malsup.com/jquery/form/">Ajax Form</a> Plugin, So Github contains source code with and without Form plugin.
</p>
1). Add the CSS and JS files in the <code>head</code> sections

<pre><code class="prettyprint">&lt;link href=&quot;http://hayageek.github.io/jQuery-Upload-File/uploadfile.min.css&quot; rel=&quot;stylesheet&quot;&gt;
&lt;script src=&quot;http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js&quot;&gt;&lt;/script&gt;
&lt;script src=&quot;http://hayageek.github.io/jQuery-Upload-File/jquery.uploadfile.min.js&quot;&gt;&lt;/script&gt;</code></pre>

<br/>

2). Add a div to <code>body</code> to handle file uploads
<pre><code class="prettyprint">&lt;div id=&quot;fileuploader&quot;&gt;Upload&lt;/div&gt;</code></pre>

<br>

3). Initialize the plugin when the <code>document</code> is ready.
<pre><code class="prettyprint">&lt;script&gt;
$(document).ready(function()
{
	$(&quot;#fileuploader&quot;).uploadFile({
	url:&quot;YOUR_FILE_UPLOAD_URL&quot;,
	fileName:&quot;myfile&quot;
	});
});
&lt;/script&gt;</code></pre>
<br/>
<b>Thats it.</b>
jQuery Ajax File uploader with progress bar is ready now.

</div>
<div class="tab-pane fade" id="single">
<p> 
<h4>Jquery File Upload </h4>
Source:
<pre><code class="prettyprint">$(&quot;#singleupload1&quot;).uploadFile({
	url:&quot;http://hayageek.com/examples/jquery/ajax-multiple-file-upload/upload.php&quot;
	});</code></pre>
Output:
<div id="singleupload1">Upload</div>

<br/><br/><br/>

<h4>Jquery File Upload with File Restrictions</h4>
Source:
<pre><code class="prettyprint">$(&quot;#singleupload2&quot;).uploadFile({
url:&quot;http://hayageek.com/examples/jquery/ajax-multiple-file-upload/upload.php&quot;,
allowedTypes:&quot;png,gif,jpg,jpeg&quot;,
fileName:&quot;myfile&quot;
});</code></pre>
Output:
<div id="singleupload2">Upload</div>

</p>
</div>
<div class="tab-pane fade" id="advanced">
<p>
<h4>Jquery Advanced File Upload </h4>
Source:
<pre><code class="prettyprint">var uploadObj = $(&quot;#advancedUpload&quot;).uploadFile({
url:&quot;http://hayageek.com/examples/jquery/ajax-multiple-file-upload/upload.php&quot;,
multiple:true,
autoSubmit:false,
fileName:&quot;myfile&quot;,
formData: {&quot;name&quot;:&quot;Ravi&quot;,&quot;age&quot;:31},
maxFileSize:1024*100,
maxFileCount:1,
dynamicFormData: function()
{
	var data ={ location:&quot;INDIA&quot;}
	return data;
},
showStatusAfterSuccess:false,
dragDropStr: &quot;&lt;span>&lt;b&gt;Faites glisser et déposez les fichiers&lt;/b&gt;&lt;/span&gt;&quot;,
abortStr:&quot;abandonner&quot;,
cancelStr:&quot;résilier&quot;,
doneStr:&quot;fait&quot;,
multiDragErrorStr: &quot;Plusieurs Drag &amp; Drop de fichiers ne sont pas autorisés.&quot;,
extErrorStr:&quot;n'est pas autorisé. Extensions autorisées:&quot;,
sizeErrorStr:&quot;n'est pas autorisé. Admis taille max:&quot;,
uploadErrorStr:&quot;Upload n'est pas autorisé&quot;

});
$(&quot;#startUpload&quot;).click(function()
{
	uploadObj.startUpload();
});</code></pre><br/>
Output:
<div id="advancedUpload">Téléchargez</div>

<br/><br/>
<div id="startUpload" class="ajax-file-upload-green">Start Upload</div>   
         
</p>
<br/>
<hr>
<p>
<h4>Jquery Delete File Option</h4>
<pre><code class="prettyprint">$(&quot;#deleteFileUpload&quot;).uploadFile({
 url: &quot;upload.php&quot;,
 dragDrop: true,
 fileName: &quot;myfile&quot;,
 returnType: &quot;json&quot;,
 showDelete: true,
 deleteCallback: function (data, pd) {
     for (var i = 0; i &lt; data.length; i++) {
         $.post(&quot;delete.php&quot;, {op: &quot;delete&quot;,name: data[i]},
             function (resp,textStatus, jqXHR) {
                 //Show Message	
                 alert(&quot;File Deleted&quot;);
             });
     }
     pd.statusbar.hide(); //You choice.
}
 });</code></pre>
Output: <div id="deleteFileUpload">File Upload (Delete)</div>

</p>

</div>
 
<div class="tab-pane fade" id="multi">
<p>                
<h4>Jquery Multiple File Upload </h4>

Source:
<pre><code class="prettyprint">$(&quot;#multipleupload&quot;).uploadFile({
url:&quot;http://hayageek.com/examples/jquery/ajax-multiple-file-upload/upload.php&quot;,
multiple:true,
fileName:&quot;myfile&quot;
});</code></pre><br/>
Output:
<div id="multipleupload">Upload</div>
</p>
</div>

<div class="tab-pane fade" id="events">
<p>
<h4>Jquery Upload File Events </h4>
Source:
<pre><code class="prettyprint">$(&quot;#eventsupload&quot;).uploadFile({
url:&quot;http://hayageek.com/examples/jquery/ajax-multiple-file-upload/upload.php&quot;,
multiple:true,
fileName:&quot;myfile&quot;,
onSubmit:function(files)
{
	$(&quot;#eventsmessage&quot;).html($(&quot;#eventsmessage&quot;).html()+&quot;&lt;br/&gt;Submitting:&quot;+JSON.stringify(files));
},
onSuccess:function(files,data,xhr)
{
	$(&quot;#eventsmessage&quot;).html($(&quot;#eventsmessage&quot;).html()+&quot;&lt;br/&gt;Success for: &quot;+JSON.stringify(data));
	
},
afterUploadAll:function()
{
	$(&quot;#eventsmessage&quot;).html($(&quot;#eventsmessage&quot;).html()+&quot;&lt;br/&gt;All files are uploaded&quot;);
	
},
onError: function(files,status,errMsg)
{
	$(&quot;#eventsmessage&quot;).html($(&quot;#eventsmessage&quot;).html()+&quot;&lt;br/&gt;Error for: &quot;+JSON.stringify(files));
}
});</code></pre><br/>
Output:
<div id="eventsupload">Upload</div>
<div id="eventsmessage"><b>Events:</b></div>
</p>
</div>

<div class="tab-pane fade" id="others">
<p>
<h4>Hide Cancel,Abort,Done Buttons</h4>
Source:
<pre><code class="prettyprint">$(&quot;#stylingupload1&quot;).uploadFile({
url:&quot;http://hayageek.com/examples/jquery/ajax-multiple-file-upload/upload.php&quot;,
multiple:true,
fileName:&quot;myfile&quot;,
showStatusAfterSuccess:false,
showAbort:false,
showDone:false,
});</code></pre><br/>
Output:
<div id="stylingupload1">Upload</div>

<br/><br/><br/>


<h4>Changing Upload Button style</h4>
Source:
<pre><code class="prettyprint">$(&quot;#stylingupload2&quot;).uploadFile({
url:&quot;http://hayageek.com/examples/jquery/ajax-multiple-file-upload/upload.php&quot;,
multiple:true,
fileName:&quot;myfile&quot;,
showStatusAfterSuccess:false,
showAbort:false,
showDone:false,
uploadButtonClass:&quot;ajax-file-upload-green&quot;
});</code></pre><br/>
Output:
<div id="stylingupload2">Upload</div>

</p>
</div>

<div class="tab-pane fade" id="doc">
<h4>jQuery Upload File API</h4> <br/>

<p><code><b>uploadFile</b></code><br/> Creates Ajax form and uploads the files to server.
<pre><code class="prettyprint">var uploadObj = $("#uploadDivId").uploadFile(options);.
</code></pre></p> <br/>

<p><code><b>startUpload</b></code><br/> uploads all the selected files. This function is used when <code>autoSubmit</code> option is set to <code>false</code>.
<pre><code class="prettyprint">uploadObj.startUpload();
</code></pre></p> <br/>

<hr/>

<h4>Options</h4>
<p><code><b>url</b></code><br/> Server URL which handles File uploads <br/></p> <br/>

<p><code><b>method</b></code><br/> Upload Form method type  <code>POST</code> or <code>GET</code>. Default is <code>POST</code></p> <br/>

<p><code><b>enctype</b></code><br/>  Upload Form enctype. Default is <code>multipart/form-data</code>.</p> <br/>

<p><code><b>formData</b></code><br/> An object that should be send with file upload. 
<code>data: { key1: 'value1', key2: 'value2' }</code></p><br/>
<p><code><b>returnType</b></code> <br>
Expected data type of the response. One of: <code>null</code>, <code>'xml'</code>, <code>'script'</code>, or <code>'json'</code>. The returnType option provides a means for specifying how the server response should be handled. 
The following values are supported:<br/>
<code>xml</code>: if returnType == 'xml' the server response is treated as XML and the 'success' callback method, if specified, will be passed the responseXML value <br/>
<code>json</code>: if returnType == 'json' the server response will be evaluted and passed to the 'success' callback, if specified <br/>
<code>script</code>: if returnType == 'script' the server response is evaluated in the global context  <br/>
</p><br/>

<p><code><b>allowedTypes</b></code> <br/>  List of comma separated file extensions. Default is <code>"*"</code>. Example: <code>"jpg,png,gif"</code> </p><br/>

<p><code><b>fileName</b></code> <br/>  Name of the file input field.Default is <code>file</code></p><br/>
<p><code><b>multiple</b></code> <br/>  If it is set to <code>true</code>, multiple file selection is allowed. Default is<code>false</code></p><br/>
<p><code><b>maxFileSize</b></code> <br/>  Allowed Maximum file Size in bytes.</p><br/>
<p><code><b>maxFileCount</b></code> <br/>  Allowed Maximum number of files to be uploaded .</p><br/>

<p><code><b>autoSubmit</b></code> <br/>  If it is set to <code>true</code>, files are uploaded automatically. Otherwise you need to call <code>.startUpload</code> function. Default is<code>true</code></p><br/>
<p><code><b>showCancel</b></code> <br/>  If it is set to <code>false</code>, Cancel button is hidden when <code>autoSubmit</code> is false. Default is<code>true</code></p><br/>
<p><code><b>showAbort</b></code> <br/>  If it is set to <code>false</code>, Abort button is hidden when the upload is in progress. Default is<code>true</code></p><br/>
<p><code><b>showDone</b></code> <br/>  If it is set to <code>false</code>, Done button is hidden when the upload is completed. Default is<code>true</code></p><br/>
<p><code><b>showDelete</b></code><br/> If it is set to <code>true</code>, Delete button is shown when the upload is completed. Default is<code>false</code>,You need to 
implement <code>deleteCallback()</code> function.

<p><code><b>showProgress</b></code> <br/> If it is set to <code>true</code>, Progress precent is shown to user. Default is<code>false</code> </p><br/>
<p><code><b>showFileCounter</b></code> <br/>If it is set to <code>true</code>, File counter is shown with name. Default is<code>true</code>File Counter style can be changed using <code>fileCounterStyle</code>. Default is <code>). </code> </p><br/>

<p><code><b>showStatusAfterSuccess</b></code> <br/>  If it is set to <code>false</code>, status box will be hidden after the upload is done. Default is<code>true</code></p><br/>

<p><code><b>onSelect</b></code> <br/>  callback back to be invoked when the files are selected or dropped.
<pre><code class="prettyprint">onSelect:function(files)
{
    files[0].name;
    return true; //to allow file submission.
}
</code></pre>
</p><br/>

<p><code><b>onSubmit</b></code> <br/>  callback back to be invoked before the file upload.
<pre><code class="prettyprint">onSubmit:function(files)
{
	//files : List of files to be uploaded
	//return false; to stop upload
}
</code></pre>
</p><br/>

<p><code><b>onSuccess</b></code> <br/>  callback to be invoked when the upload is successful.
<pre><code class="prettyprint">onSuccess:function(files,data,xhr)
{
	//files: list of files
	//data: response from server
	//xhr : jquer xhr object
}</code></pre>

<p><code><b>afterUploadAll</b></code> <br/>  callback to be invoked when all the uploads are done.
<pre><code class="prettyprint">afterUploadAll:function()
{

}</code></pre>

</p><br/>
<p><code><b>onError</b></code> <br/>  callback back to be invoked when the upload is failed.
<pre><code class="prettyprint">onError: function(files,status,errMsg)
{
	//files: list of files
	//status: error status
	//errMsg: error message
}</code></pre>
</p><br/>
<p><code><b>deleteCallback</b></code> <br/>  callback  to be invoked when the user clicks on Delete button..
<pre><code class="prettyprint">deleteCallback: function(data,pd)
{
	for(var i=0;i&lt;data.length;i++)
	{
	 	$.post(&quot;delete.php&quot;,{op:&quot;delete&quot;,name:data[i]},
	    function(resp, textStatus, jqXHR)
	    {
			//Show Message	
			alert(&quot;File Deleted&quot;);	    
	    });
	 }		
	pd.statusbar.hide(); //You choice to hide/not.

}</code></pre>
</p><br/>
<p><code><b>uploadButtonClass</b></code> <br/> Upload Button class. Default is<code>ajax-file-upload</code></p><br/>
</div>

<div class="tab-pane fade" id="server">
<p>
<h3> PHP code for handling Multiple file uploads </h3>
upload.php Source:
<pre><code class="prettyprint">&lt;?php
$output_dir = &quot;uploads/&quot;;
if(isset($_FILES[&quot;myfile&quot;]))
{
	$ret = array();

	$error =$_FILES[&quot;myfile&quot;][&quot;error&quot;];
	//You need to handle  both cases
	//If Any browser does not support serializing of multiple files using FormData() 
	if(!is_array($_FILES[&quot;myfile&quot;][&quot;name&quot;])) //single file
	{
 	 	$fileName = $_FILES[&quot;myfile&quot;][&quot;name&quot;];
 		move_uploaded_file($_FILES[&quot;myfile&quot;][&quot;tmp_name&quot;],$output_dir.$fileName);
    	$ret[]= $fileName;
	}
	else  //Multiple files, file[]
	{
	  $fileCount = count($_FILES[&quot;myfile&quot;][&quot;name&quot;]);
	  for($i=0; $i &lt; $fileCount; $i++)
	  {
	  	$fileName = $_FILES[&quot;myfile&quot;][&quot;name&quot;][$i];
		move_uploaded_file($_FILES[&quot;myfile&quot;][&quot;tmp_name&quot;][$i],$output_dir.$fileName);
	  	$ret[]= $fileName;
	  }
	
	}
    echo json_encode($ret);
 }
 ?&gt;</code></pre><br/>


</p>
<br/>
delete.php Source code:
<pre><code class="prettyprint">&lt;?php
$output_dir = &quot;uploads/&quot;;
if(isset($_POST[&quot;op&quot;]) &amp;&amp; $_POST[&quot;op&quot;] == &quot;delete&quot; &amp;&amp; isset($_POST[&#39;name&#39;]))
{
	$fileName =$_POST[&#39;name&#39;];
	$filePath = $output_dir. $fileName;
	if (file_exists($filePath)) 
	{
        unlink($filePath);
    }
	echo &quot;Deleted File &quot;.$fileName.&quot;&lt;br&gt;&quot;;
}
?&gt;</code></pre>

<h3> Server settings for Large file uploads</h3>
<b>php.ini settings </b>
<pre><code class="prettyprint">max_file_uploads = 2000
upload_max_filesize = 40000M
max_input_vars = 10000
post_max_size = 40000M
</code></pre>
<br>
<b>httpd.conf settings</b>   
 <pre><code class="prettyprint">php_value suhosin.post.max_vars 10000
php_value suhosin.request.max_vars 10000
php_value suhosin.get.max_array_depth 2000
php_value suhosin.get.max_vars 10000
php_value suhosin.upload.max_uploads 2000
</code></pre>   
</div>
            
 </div>
</section>

<br/>
<ins class="adsbygoogle"
     style="display:inline-block;width:970px;height:90px"
     data-ad-client="ca-pub-0923466578214929"
     data-ad-slot="6444020521"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
 

<div class="row">
<div class="well">
<b>Please Share it with your friends if you like the plugin:</b><br/><br/>
<a data-url="http://hayageek.com/docs/jquery-upload-file.php" href="https://twitter.com/share" class="twitter-share-button" data-count="horizontal"></a>
<div data-href="http://hayageek.com/docs/jquery-upload-file.php" class="g-plusone" data-annotation="inline" data-size="medium" data-width="120"></div>
<div data-href="http://hayageek.com/docs/jquery-upload-file.php" class="fb-like" data-layout="button_count" data-send="false" data-show-faces="false" data-width="120"></div>

<br/> <br/>

<div class="g-person" data-width="450" data-href="//plus.google.com/118255177648356108079" data-layout="landscape" data-rel="author"></div>
<form id="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post"> 
 <input type="hidden" name="cmd" value="_xclick"> 
 <input type="hidden" name="business" value="rskusuma@yahoo.com"> 
 <input type="hidden" name="item_name" value="Support Hayageek.com"> 
 <input type="hidden" name="buyer_credit_promo_code" value=""> 
 <input type="hidden" name="buyer_credit_product_category" value=""> 
 <input type="hidden" name="buyer_credit_shipping_method" value=""> 
 <input type="hidden" name="buyer_credit_user_address_change" value=""> 
 <input type="hidden" name="no_shipping" value="0"> 
 <input type="hidden" name="no_note" value="1"> 
 <input type="hidden" name="currency_code" value="USD"> 
 <input type="hidden" name="tax" value="0"> 
 <input type="hidden" name="lc" value="US"> 
 <input type="hidden" name="bn" value="PP-DonationsBF"> 
 <div><input id="butt" type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!"> </div>
</form>	
</div>
</div>
<div class="row">
<div id="disqus_thread">Loading Comments...</div>
</div>

     <!-- Footer
      ================================================== -->
      <hr>

      <footer id="footer">
        <p class="pull-right"><a href="#top">Back to top</a></p>
        <div class="links">
          <a href="http://hayageek.com" >Blog</a>
        </div>
      </footer>

    </div><!-- /container -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
<script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>
<script src="jquery.uploadfile.min.js"></script>

<script>	

function loadSocial(){
	
		$.getScript('http://platform.twitter.com/widgets.js');
		 $.getScript("http://connect.facebook.net/en_US/all.js#xfbml=1", function () {
            FB.init({ status: true, cookie: true, xfbml: true });
        });
        $.getScript('https://apis.google.com/js/plusone.js',function()
        {
         	$(".g-plusone").each(function () {
        		    gapi.plusone.render($(this).get(0));
		        });
        });
	
}
	$(document).ready(function()
	{

		$('a[rel=tooltip]').tooltip({'placement': 'bottom'});
	});
	
$(document).ready(function()
{
	$("#singleupload1").uploadFile({
	url:"http://hayageek.com/examples/jquery/ajax-multiple-file-upload/upload.php"
	});

	$("#singleupload2").uploadFile({
	url:"http://hayageek.com/examples/jquery/ajax-multiple-file-upload/upload.php",
	allowedTypes:"png,gif,jpg,jpeg",
	fileName:"myfile"
	});


	$("#multipleupload").uploadFile({
	url:"http://hayageek.com/examples/jquery/ajax-multiple-file-upload/upload.php",
	multiple:true,
	fileName:"myfile"
	});
	
	var uploadObj = $("#advancedUpload").uploadFile({
	url:"http://hayageek.com/examples/jquery/ajax-multiple-file-upload/upload.php",
	multiple:true,
	autoSubmit:false,
	fileName:"myfile",
	formData: {"name":"Ravi","age":31},
	maxFileSize:1024*100,
	maxFileCount:1,
	dynamicFormData: function()
	{
		var data ={ location:"INDIA"}
		return data;
	},
	showStatusAfterSuccess:false,
	dragDropStr: "<span><b>Faites glisser et déposez les fichiers</b></span>",
    abortStr:"abandonner",
	cancelStr:"résilier",
	doneStr:"fait",
	multiDragErrorStr: "Plusieurs Drag &amp; Drop de fichiers ne sont pas autorisés.",
	extErrorStr:"n'est pas autorisé. Extensions autorisées:",
	sizeErrorStr:"n'est pas autorisé. Admis taille max:",
	uploadErrorStr:"Upload n'est pas autorisé"
	});
	$("#startUpload").click(function()
	{
		uploadObj.startUpload();
	});
	
	var deleteuploadObj = $("#deleteFileUpload").uploadFile({url: "upload.php",
 dragDrop: true,
 fileName: "myfile",
 returnType: "json",
 showDelete: true,
 deleteCallback: function (data, pd) {
     for (var i = 0; i < data.length; i++) {
         $.post("delete.php", {op: "delete",name: data[i]},
             function (resp,textStatus, jqXHR) {
                 //Show Message	
                 alert("File Deleted");
             });
     }
     pd.statusbar.hide(); //You choice.

 }
 });
	
	$("#eventsupload").uploadFile({
	url:"http://hayageek.com/examples/jquery/ajax-multiple-file-upload/upload.php",
	multiple:true,
	fileName:"myfile",
	onSubmit:function(files)
	{
		$("#eventsmessage").html($("#eventsmessage").html()+"<br/>Submitting:"+JSON.stringify(files));
	},
	onSuccess:function(files,data,xhr)
	{
		$("#eventsmessage").html($("#eventsmessage").html()+"<br/>Success for: "+JSON.stringify(data));
		
	},
	afterUploadAll:function()
	{
		$("#eventsmessage").html($("#eventsmessage").html()+"<br/>All files are uploaded");
		
	
	},
	onError: function(files,status,errMsg)
	{
		$("#eventsmessage").html($("#eventsmessage").html()+"<br/>Error for: "+JSON.stringify(files));
	}
	});
	
	
	$("#stylingupload1").uploadFile({
	url:"http://hayageek.com/examples/jquery/ajax-multiple-file-upload/upload.php",
	multiple:true,
	fileName:"myfile",
	showStatusAfterSuccess:false,
	showAbort:false,
	showDone:false,
	});

	$("#stylingupload2").uploadFile({
	url:"http://hayageek.com/examples/jquery/ajax-multiple-file-upload/upload.php",
	multiple:true,
	fileName:"myfile",
	showStatusAfterSuccess:false,
	showAbort:false,
	showDone:false,
	uploadButtonClass:"ajax-file-upload-green"
	});
	
});
</script>	
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-37706919-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
jQuery(document).ready(function($) {
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        var disqus_shortname = 'hayageek'; // required: replace example with your forum shortname
		var disqus_loaded=false;
		if($("#disqus_thread").length > 0)
		{
			$(window).scroll(function () 
			{
				if(!disqus_loaded)
				{
				loadSocial();
				/* * * DON'T EDIT BELOW THIS LINE * * */
					var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
					dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
					(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
					disqus_loaded = true;
				}
			});
		}
});
</script>
  </body>
</html>
