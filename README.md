# SearchEngine-DataMining

Developed a working custom search engine using Bing API, in PHP, HTML, CSS and Bootstrap. 
First the input query is taken and the 20 search results are shown. 
At the bottom of the page there is a textbox to enter 5 most relevant results among the 20 results and a button to re-rank them. 
On clicking the button, it takes to another page where it shows the re-ranked results.
Both the initial and relevant results are written to 2 documents . 
When the re-rank button is clicked, the cosine similarity is calculated for the initial search results and the 5 relevant results . 
In descending order of the similarities , the re-ranked results are displayed.
