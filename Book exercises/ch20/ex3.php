<?php

/*

ch 20: ex  3

To have the thumbnail clicked through a larger version ot the uploaded file, we will modify 
the application/views/files/view.html template with the help of an image modal. 

Hint: https://www.w3schools.com/howto/tryit.asp?filename=tryhow_css_modal_img

Rewrite the application/views/files/view.html template (listing 20-15, p.335-336)

*/


?>

<style>

	#myImg {
	  border-radius: 5px;
	  cursor: pointer;
	  transition: 0.3s;
	}

	#myImg:hover {opacity: 0.7;}

	.modal {
	  display: none; 
	  position: fixed; 
	  z-index: 1; 
	  padding-top: 100px; 
	  left: 0;
	  top: 0;
	  width: 100%; 
	  height: 100%; 
	  overflow: auto; 
	  background-color: rgb(0,0,0); 
	  background-color: rgba(0,0,0,0.9); 
	}

	
	.modal-content {
	  margin: auto;
	  display: block;
	  width: 80%;
	  max-width: 700px;
	}

	
	#caption {
	  margin: auto;
	  display: block;
	  width: 80%;
	  max-width: 700px;
	  text-align: center;
	  color: #ccc;
	  padding: 10px 0;
	  height: 150px;
	}

	/* Add Animation */
	.modal-content, #caption {  
	  -webkit-animation-name: zoom;
	  -webkit-animation-duration: 0.6s;
	  animation-name: zoom;
	  animation-duration: 0.6s;
	}

	@-webkit-keyframes zoom {
	  from {-webkit-transform:scale(0)} 
	  to {-webkit-transform:scale(1)}
	}

	@keyframes zoom {
	  from {transform:scale(0)} 
	  to {transform:scale(1)}
	}

	.close {
	  position: absolute;
	  top: 15px;
	  right: 35px;
	  color: #f1f1f1;
	  font-size: 40px;
	  font-weight: bold;
	  transition: 0.3s;
	}

	.close:hover,
	.close:focus {
	  color: #bbb;
	  text-decoration: none;
	  cursor: pointer;
	}

	@media only screen and (max-width: 700px){
	  .modal-content {
		width: 100%;
	  }
	}
</style>

<h1>View Files</h1>
	<table>
		<tr>
			<th>Thumbnail</th>
			<th>Change</th>
		</tr>
		{foreach $file in $files}
			<tr>
				<td><img id="myImg"  src="/thumbnails/{echo $file->id}" /></td>
				<td>
					{if $file->deleted}
						<a href= "/files/undelete/{echo $file->id}.html">undelete</a>
					{/if}
					{else}
						<a href= "/files/delete/{echo $file->id}.html">delete</a>
					{/else}
				</td>
			</tr>
		{/foreach}
	</table>


<div id="myModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div>

<script>

	var modal = document.getElementById("myModal");

	var img = document.getElementById("myImg");

	var modalImg = document.getElementById("img01");

	    var captionText = document.getElementById("caption");
		  img.onclick = function(){
		  modal.style.display = "block";
		  modalImg.src = this.src;
		  captionText.innerHTML = this.alt;
          }

	var span = document.getElementsByClassName("close")[0];

	span.onclick = function() { 
	  modal.style.display = "none";
	}
  
</script>



