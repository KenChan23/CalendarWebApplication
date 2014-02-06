var count = 3;

	function countdown()
	{

		count--;

		document.getElementById("countdown").innerHTML = count;

		if(count == 0)
		{

			// window.location.href = "index.php";
			return;

		}

		else
		{

			setTimeout("countdown()", 1000);

		}

	}

setTimeout("countdown()", 1000);