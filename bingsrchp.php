<!DOCTYPE html>
<html>
	<head>
		<title>Bing Search</title>
			<style>
				body {
					background-image: url("search.jpg");
					background-repeat: no-repeat;
					background-position: right top; 
					}
				h2 {
					color:blue;
					}
				a {
					font-style: bold;
					font-size: 20px;
					}
				p {
					white-space:pre-wrap; 
					width:60ex;
					font-size: 15px;
					font-family: serif;
					}
			</style>
		</title>
	</head>
	<body>
		<?php
		session_start();
		 
		if (isset($_POST['submit'])) 
		{
		$acctKey = 'ULun0YwaUHVtbGw49eO8UwK/dAoXiCiUliG6NLV7Poc';
		$rootUri = 'https://api.datamarket.azure.com/Bing/Search';
		$query = $_POST['searchText'];
		$serviceOp ='Web';
		$market ='en-us';
		$query = urlencode("'$query'");
		$requestUri = "$rootUri/$serviceOp?\$format=json&Query=$query";
		$auth = base64_encode("$acctKey:$acctKey");
		$data = array(  
					'http' => array(
                        'request_fulluri' => true,
                        'ignore_errors' => true,
                        'header' => "Authorization: Basic $auth"
                        )
					);
		$context = stream_context_create($data);
		$response = file_get_contents($requestUri, 0, $context);
		$response=json_decode($response);
		echo "<pre>";

		$i = 0;
		$ResultsStop = array();
		foreach($response->d->results as $value)
		{
	
			$newResults[$i] = array($i+1,$value->Url,$value->Description);
			$ResultsStop[$i] = array($i+1,$value->Url,$value->Description);
			$i++;
		}	
			for($i=0;$i<20;$i++)
			{
			echo "(".$newResults[$i][0].")    ".'<a href='.$newResults[$i][1].'> '.$newResults[$i][1]. '</a><br><p>';
			echo($newResults[$i][2] .' </p>');
			}
 
			echo "</pre>";
			
			error_reporting(E_ERROR | E_PARSE);
			// Removing the stop words from the search results:
			$stopwords = array("a","an","and","are","as","at","be","for","from","in","is","it","of","or","on","that","the","to","was","were","will","with");
			
			for($i=0;$i<20;$i++)
			{	
				
				$stringStop = $ResultsStop[$i][2];
								
				foreach ($stopwords as &$word) {
					$word = '/\b' . preg_quote($word, '/') . '\b/';
				}
				$stringStop = preg_replace($stopwords, '', $stringStop);
				
				if(! empty($stringStop)){
					
					$ResultsStop[$i][2] = $stringStop;
					}
			}
			file_put_contents('testfile_orig.txt', print_r($ResultsStop, true));

		}
		
		if(!empty($newResults)){
			$_SESSION['value'] = $newResults;
			$_SESSION['stopwrd'] = $ResultsStop;
		}
		?>
		<br><br><hr><br>
		<form method="post" action="newbingsrchp.php">
		<h2> From the above results, Enter your 5 most relevant search result numbers separated by comma(,): </h2>
		<input type='text' id='RankText' name='RankText' />
		<input type="submit" value="Re-Rank!!" name="submit2" id="searchButton" />
		<br><br><hr>
		</form>

	</body>
</html>
