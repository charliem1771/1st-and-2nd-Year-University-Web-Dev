document.addEventListener("DOMContentLoaded", handleDocumentLoad);

function handleDocumentLoad()
{
	//declaring variables
	var videoPlayer = document.querySelector("video"); //This line uses the query slector to get the video from the HTML
	var muteButton = document.getElementById("Mute"); //This line gets the mute button from the HTMl and assigns it to a variable
	var playButton = document.getElementById("Play"); //This line gets the variable play to get the play button tag
	var stopButton = document.getElementById("Stop"); //This line gets the variable stop to get the stop button tag
	var scrubSlider = document.getElementById("seekBar"); //This line gets a range slider from the HTML and assigns it to a variable
	var volumeControl = document.getElementById("volumeBar"); //This line gets the volumeBar element from the HTML and assigns it to a variable
	var playSpeed =  document.getElementById("speeds"); //This gets the dropdown menu and assigns it to a variable
	var playBackTime = document.getElementById("currentPlayBack"); //Gets the currentPlayback input
	var videoDuration = document.getElementById("durationField"); //This gets the durationField text element
	var fastForwardBtn = document.getElementById("fastForwardBtn");//this gets the fastforward button and assigns it to a variable
	var audioVolume; //creates the audioVolume variable
	
	//Adding event listeners
	playButton.addEventListener("click",playVideo); //This will add the click event to the playButton variable, then it will allow for declaration
	muteButton.addEventListener("click",muteUnMute); //of a function known as Play which works with the click event
	stopButton.addEventListener("click",stopVideo); //Allows the stopVideo funcion to access click
	scrubSlider.addEventListener("input",scrubVideo); //Gets input for the scrub slider and allows it to be assigned to the scrubVideo function
	videoPlayer.addEventListener("timeupdate",scrubValue); //Allows this function to use the timeupdate command
	volumeControl.addEventListener("input",changeVolume);  //Allows this function to get input from volumeControl
	muteButton.addEventListener("click",changeVolume);  //Allows this function to the click from from muteButton
	videoPlayer.addEventListener("durationchange", displayDuration); //Allows this function to use the change durationchange command
	videoPlayer.addEventListener("timeupdate",currentPlayBackTime); //Allows timeupdate to be used in playBack
	playSpeed.addEventListener("change", speedUpVideo); //Allows this funcion to use the change command
	fastForwardBtn.addEventListener("click", playDoubleSpeed); //Assigns the click function to the fastForwardDbl function
	fastForwardBtn.addEventListener("change", playDoubleSpeed); //Assigns the change function to the fastForwardDbl function
	fastForwardBtn.addEventListener("dblclick", playNormalSpeed); //Assigns the dblclick function to the normalSpeed function
	fastForwardBtn.addEventListener("change", playNormalSpeed); //Assigns the change funcion to the normalSpeed function
	fastForwardBtn.addEventListener("mousedown", playTripleSpeed); //Assigns the mousedown function to the trplSpeed function
	fastForwardBtn.addEventListener("change", playTripleSpeed); //Assigns the change function to the trplSpeed function
	
	//sets the video to play at 3 times speed
	function playTripleSpeed()
	{
		videoPlayer.playbackRate = 3.0; //uses the playbackRate property to set the speed to 3
		fastForwardBtn.innerHTML = ">>>"; //changes the HTML button to appear as three arrows
	}
	
	//sets the video to play at normal speed
	function playNormalSpeed()
	{
		videoPlayer.playbackRate = 1.0; //uses the playbackRate property to set the speed to 2
		fastForwardBtn.innerHTML = ">"; //changes the HTML button to appear as two arrows
	}
	
	//sets the video to play at 2 times speed
	function playDoubleSpeed() 
	{
		videoPlayer.playbackRate = 2.0; //uses the playbackRate property to set the speed to 1
		fastForwardBtn.innerHTML = ">>"; //changes the HTML button to appear as one arrow
	}
	
	//allows the video speed to be based off the selected option
	function speedUpVideo()
	{
		videoPlayer.playbackRate = playSpeed.value; //Makes the playback rate,match the value in playspeed
	}												
	
	//Gets the current time of the video
	function currentPlayBackTime()
	{
		/*Divides the current video time by 60 to make it display in applicable time along with using math.floor to round it down to the nearest 
		whole number,allowing for proper display*/
		var mins = Math.floor(videoPlayer.currentTime/60);
		
		/*Gets the remainder of the current time using modulos to get the current second the video is at,math.floor is used to round
		it down to the nearest whole number, allowing for proper display*/
		var secs = Math.floor(videoPlayer.currentTime%60);
		
		if(mins <10) //checks to see if mins is less than 10
		{
			mins = "0" + mins; //Allows mins to always display a 0
		}
		
		if(secs <10) //checks to see if secs is less than 10
		{
			secs = "0" + secs; //Allows secs to always display a 0
		}
		
		playBackTime.value = mins + ":" + secs; //makes the playBackTime variable equal to the current minutes and seconds
	}
	
	//Displays the duration of the video
	function displayDuration() 
	{
		/*Divides the duration of the video by 60 to allow it to display the duration in applicable time, math.floor is used to round it
		down to the nearest whole number,allowing for proper display*/
		var mins = Math.floor(videoPlayer.duration /60);
		/*Gets the remainder of the duration using modulos to get the current second the video is at,math.flooris used to round it down
		to the nearest whole number, allowing for proper display*/
		var secs = Math.floor(videoPlayer.duration %60);
		
		if(mins <10) // checks to see if mins is less than 10
		{ 
			mins = "0" + mins; //Allows mins to always display a 0
		}
		
		if(secs <10) // checks to see if secs is less than 10
		{
			secs = "0" + secs; //Allows secs to always display a 0
		}
		
		videoDuration.value = mins + ":" + secs; //sets the video duration variable to be equal to the videos total duration
	}
	
	//allows for volume change based on the slider position
	function changeVolume()
	{
		if(videoPlayer.muted === false)//If statement to make volume bar 0
		{
			videoPlayer.volume = 1; //sets videoPlayer volume to 1 so we have something to manipulate
			/*Allows the audioVolume to be controlled by the value of volume control, the divison of 100
			allows for precise manipulation of the volume*/
			audioVolume = videoPlayer.volume*volumeControl.value/100;
			videoPlayer.volume = audioVolume;//sets the videoPlayer volume to be equal to audioVolume
		}
		else if(videoPlayer.muted === true)
		{
			volumeControl.value = 0; //setting the volume control value to 0 if the video is muted to make the volume bar 0
		}
	}
	
	//sets the scrubvalue
	function scrubValue() 
	{	
		/*Makes the value of scrubslider to be equal to the currenttime divided by the duration
		it is then times by 100 to prevent any rounding issues*/
		scrubSlider.value = (videoPlayer.currentTime/videoPlayer.duration)*100;
	}
	
	//allows for scrubbing through the video
	function scrubVideo() 
	{
		/*Set the scrubTime variable to be equal to videoPlayer's duration time 
		scrubSlider's value which is then divided by 100 to prevent any rounding issues*/
		var scrubTime = videoPlayer.duration*(scrubSlider.value/100); 
		videoPlayer.currentTime = scrubTime; //sets the currentTime of videoPlayer to equal the scrub time
	}
	
	//allows for muting and unmuting of videos
	function muteUnMute()
	{
		if(videoPlayer.muted === false)//check's if the video being meeting is false if not make video muted
		{
			videoPlayer.muted = true; //mutes the video
			muteButton.innerHTML = "Muted"; //makes the button say muted
		}
		else //else make muted false dependant on button press
		{
			videoPlayer.muted = false; //Make's muted false
			muteButton.innerHTML = "unMuted"; //makes the button say unmuted		
		}
	}
	
	//plays or pauses the video 
	function playVideo() 
	{
		if(videoPlayer.paused === true) //checking if the video is paused
		{
			changeVolume(); //calls volume slider if the video is playing
			videoPlayer.play(); //plays the video
			playButton.innerHTML = "Playing"; //makes the button say playing
		}
		else //if video is paused unpause it dependant on button press
		{
			videoPlayer.pause(); //pauses the video
			playButton.innerHTML = "Paused"; //Makes the button say paused
		}
	}
	
	//stops the video and sets the currenttime to 0
	function stopVideo() 
	{
		if(videoPlayer.currentTime > 0) //if the button is pressed and videoPlayer.currenTime is > 0
		{
			videoPlayer.currentTime = 0; // set current time to 0
			videoPlayer.pause(); //pause the video
			playButton.innerHTML = "Play"; //make the button say play
		}
	}
}