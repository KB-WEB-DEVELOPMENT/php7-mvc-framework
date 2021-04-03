<?php


/*

ch 17: ex 3

To toggle ("show/hide") the message reply form when all earlier messages are listed, 

we use the following javascript code at the bottom.

Modify listing 17-20 (p.287)  
 
*/

// file : application/views/home/index.html

{if isset($messages)}
	
	{foreach $message in $messages}
	
		{echo $message->body}<br />
		
			{foreach $reply in Message::fetchReplies($message->id)}
				 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{echo $reply->body}<br />
			{/foreach}
			
			<button onclick="toggleReplyForm()">Click to reply to the latest message</button>

			<div id="toggleFormId">
			
				<form method="post" action="/messages/add.html">
					<textarea name="body"></textarea>
					<input type="hidden" value="{echo $message->id}" name="message" />
					<input type="submit" name="share" value="share" />
				</form>

			</div>

	{/foreach}
	
{/if}

?>

<script>

	function toggleReplyForm() {
		
	  var toggleForm = document.getElementById("toggleFormId");
	  
	  if (toggleForm.style.display === "none") {
	  
		toggleForm.style.display = "none";

	  } else {

		toggleForm.style.display = "block";
	  }

	}
 
</script>

