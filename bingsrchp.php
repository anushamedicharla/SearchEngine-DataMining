<!DOCTYPE html>
<html>
	<head>
		<title>Bing Search</title>
		<link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
		<link href="style.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div class='container'>
			<a href="bingsrch.html"><img class="homeSearch" src="homeSearch.gif"></a>
			<h2 id='secHeading' class='text-center'> Top 20 Search Results </h2>
				<div class='row'>
					<div class='col-xs-12'>
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
		echo "	";

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
			echo "<div class='panel panel-default'>"."<a href=".$newResults[$i][1]." target='_blank'><div class='panel-heading'>".$newResults[$i][1]."</div><div class='panel-body'>";
			echo($newResults[$i][2] .'</div></a></div>');
			}

			echo "";
			
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
		</div></div>
			<div class="row">
				<div class="col-xs-12">
					<div class="bottomSearch">
						<form method="post" action="newbingsrchp.php" autocomplete="off">
							<h3 class="text-center"> From the above results, Enter your 5 most relevant search result numbers separated by comma(,):</h3>
							<input type='text' class="form-control" id='RankText' name='RankText' required />
							<input class="btn btn-info text-center" type="submit" value="Re-Rank" name="submit2" id="searchButton" />
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
