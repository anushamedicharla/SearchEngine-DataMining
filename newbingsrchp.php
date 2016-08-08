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
			<h2 id='secHeading' class='text-center'> Reranked Search Results </h2>
				<div class='row'>
					<div class='col-xs-12'>
		<?php
			session_start();		 
			if (isset($_POST['submit2'])) 
			{
				if(isset($_POST['RankText']))
				{
					$string = $_POST['RankText'];
					$array = explode(',', $string);
	
					if(isset($_SESSION['value'])){
					$arr1 = array();
					$arr1 = $_SESSION['value'];
					
					$arr1Stop = array();
					$arr1Stop = $_SESSION['stopwrd'];
					
					$arr2 = array();
					$j = 0;
					// Removing the stop words from the 5 most relevant results:
					$stopwords = array("a","an","and","are","as","at","be","for","from","in","is","it","of","or","on","that","the","to","was","were","will","with");
					
					foreach($array as $ar)
					{	
						$arr2[$j][0] = $arr1[$ar-1][0];
						$arr2[$j][1] = $arr1[$ar-1][1];
						$arr2[$j][2] = $arr1[$ar-1][2];
						
						$stringStop = $arr2[$j][2];
							
							foreach ($stopwords as &$word) {
								$word = '/\b' . preg_quote($word, '/') . '\b/';
							}
							$stringStop = preg_replace($stopwords, '', $stringStop);
							$arr2[$j][2] = $stringStop;
							
							$j++;
					}
					
					
						
					file_put_contents('testfile1.txt', print_r($arr2, true));
					// Finding the cosine similarity between original search results and 5 relevant results :
					function cosineSimilarity($terms1, $terms2)
					{	
						$counts1 = array_count_values($terms1);
						$counts2 = array_count_values($terms2);
						$totalScore = 0;
						foreach ($terms2 as $term) {
							if (isset($counts1[$term])) $totalScore += $counts1[$term] * $counts2[$term];
						}
						return $totalScore / (count($terms1) * count($terms2));
					}
					$cosSimArr = array();
					for($p=0;$p<20;$p++)
					{
						for($q=0;$q<5;$q++){
							$cosSim = cosineSimilarity($arr1Stop[$p], $arr2[$q]);
							
							$cosSimArr[$p] = array($cosSim,$p);
							if($cosSim!=0) break;
						}
						
					}
					// rearranging results based on the cosine similarity values in descending order:
					rsort($cosSimArr);
					for($g=0;$g<20;$g++)
					{	
						$index1 = $cosSimArr[$g][1];
						echo "<div class='panel panel-default'>"."<a href=".$arr1[$index1][1]."target='_blank'><div class='panel-heading'>".$arr1[$index1][1]. "</div><div class='panel-body'>";
						echo($arr1[$index1][2] .'</div></a></div>');
					}
				}
				}
			}
		?>
		</div>
		</div>
		</div>
	</body>
</html>