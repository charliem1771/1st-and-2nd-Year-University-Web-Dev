<?php


// execute the header script:
require_once "header.php";

if (!isset($_SESSION['loggedInSkeleton']))
{
	// user isn't logged in, display a message saying they must be:
	echo "You must be logged in to view this page.<br>";
}
else
{
	echo <<<_END
	<h2>Analysis of other Survery Websites</h2>
	<p>On this page there will be a detailed analysis of the following survey sites:</p>
	<ul>
		<li><a href = "https://www.google.com/forms/about/">Google Forms</a></li>
		<li><a href = "hhtps://www.surveymonkey.com/">Survey Monkey</a></li>
		<li><a href = "https://surveyplanet.com/">Survey Planet</a></li>
	</ul>
	<h3>Google Forms Analysis</h3>

	<p>The first thing I noticed about Google forms is the sign up which is incredibly simple, just have a Google account
	yet this poses a problem what if the user does not have a google account? If they don't the user has to make a Google account
	what if the user does not want to create a Google account or use the Google suite. At a glance the sign in system is fantastic
	yet with some thought it is actually quite uninviting for new users. The site is incredibly easily to use simply click the big plus
	button then you can start creating surveys. Having not used google forms before I easily created a survey with multiple choice questions
	tick box questions and paragraph answers. The design flow of the site is incredibly easy to use due to Googles excellent design principles.
	Sending surveys to people you want to is also simple basically just hit send and type there email address and your done.
	The surveys are presented to the user in a neat modern fashion which is often common with Google software the deisgn of the survey makes
	it incredibly easy to use when sent to a user. When using Google forms to create surveys there is a total of 12 options to select from ranging from a simple 
	multiple choice answer to a file upload system. I would say Google forms offers all the necessary options one could need while creating a survey
	which allows for a wide range of survey creation. The analysis tools allow you to view all the surveys as a bar chart or a pie chart along with 
	allowing you to view individual submissions from each user.</p>

	<p>To conclude Google forms is a excellent exmaple of a well made and designed survey site which is easy to use with lots of data analysis features.
	Yet eventhough it is very accessible it does require a Google account which could cause users to go for other survey sites. Yet I would say this
	is the only weakness of Google forms since it excels to near exccelence in almost every other aspect.</p>

	<h3>Key things I learned from this analysis</h3>
	<ul>
		<li>Make sending surveys to other users simple</li>
		<li>Build in lots of analysis tools for the data</li>
		<li>Allow for a wide range of question options for survey creation</li>
		<li>Try and not lock account creation to an exisiting service</li>
	</ul>

	<h3>Survey Moneky Analysis</h3>

	<p>The first thing I noticed and liked about survey moneky was the sign up process it had a traditional signup form with a which you had to fill out.
	Yet it also allowed you to sign up using acccounts from Google,Facebook and Office 365, allowing the site to be very accessible and not force you into
	any pre-established sites like Google forms does. Straight away Survey Monkey felt bulky and over bearing it was simple to use but seems like it was 
	trying to do to much. The buttons felt unwelcoming you can't tell if you have filled out all the required details since the buttons look null 
	the site lacks a few key modern design principals it just doesnt feel right at all. I also noticed it has 14 options for questions a very large and diverse 
	amount yet 2 of those options where premium which makes sense but it is still something to note. I think the way you create questions
	feels bad it doesn't look nice at all. Yet even though it looked bulky the method used to send surverys was excellent it allowed you to email,send weblinks or
	even embed the survey in your own site. This variety of options could prove useful for many users who wish to make there survey accessible faster and not have to
	individually add each person to a email chain. Yet for some reasion it took me 5 minutes to receive my own survey yet due to the bad design of survey monkeys site I 
	could not tell if this was intentional or a error. When the recipientant recieves the survey it is embedded in a email you have to click on a box that looks like a survey
	whcih just seems confsuing like your going to take the survey in a email. Then upon clicking this box with a survey in you are sent to another page to take the survey, the
	design of the survey is pretty standard, yet upon completing the survey it sends you to Survey Monkeys site and shows you a advert. The entire method of survey completion is
	just bulky and terrible its needlessly confusing and contains ads. The best part of Survey Monkeys whole site is the results analysis system allowing you to display bar charts
	pie charts line graphs etc. You can customize the colouring get the display to show data percentages the data analysis methods are excellent and could easily become very useful
	when analysing the data.</p>
	
	<p>To conclude eventhough survey monkey has a excellent sign up system the actual site and survey creation features are not very good and the whole sites feels bulky
	there is simply to much going on. The site feels over comborsum and heavy, the methods used to send surveys are quite good but it is hard to tell if the survey has been sent.
	When a user completes a survey it is just ugly and a bit irratating. Yet the data analysis features are fantastic it is probably the
	only part of the site that's easy to use and there's plenty of features as-well. Overall Survey Monkey is a messy site with lots of options buried in poor design and confusing methods
	yet the data analysis features are great and the site has a lot to offer in terms of what you can actually do,but the ease of use and design is just awful.</p>
	
	<h3>Key things I learned from this analysis</h3>
	<ul>
		<li>Allow for multiple login methods</li>
		<li>Allow for a wide range of data analysis systems</li>
		<li>Design the site well don't make it bulky terrible and annoying to use</li>
		<li>Make the method of sending surveys clear to both the sender and the person receiving it</li>
		<li>Allow for the surveys to be sent via a normal link and allow them to be embedded</li>
	</ul>
	
	<h3>Survey Planet</h3>
	
	<p>It was very simple to create a user account it was just a standard login procedure sign up with an email address verify the email address. Next came survey creation which was so simple it
	was clear what you where meant to do and how you should do it the design flow of the site was great it made creation and customization of the surveys simple allowing me to quickly create a survey.
	The only thing I can say about the ease of use and survey creation is that some features where locked behind a paywall, along with a lack of question types it only had 10 and it lacked the option 
	of a file upload question type. Yet sending the surveys was very easy simply use the link provided and send it to people, I believe this is the best method for sending the surveys since it is simple
	and it doesn't involve the recipientant having to use there email address it is just simpler and easier, when the survey is sent it is nicely laid out and simple to complete. Completing the surveys was
	simple for the user it was very well designed and laid out, yet the actual survey analysis tools where very lacking without paying for more features you could not use much more than a pie chart to represent data.
	Even though the rest of the site is well designed,simple and very good to use the actual survey analysis tools are very lacking in many regards.</p>
	
	<p>To conclude Survey Planet is a excellent survey site in terms of design and usage it flows incredibly well it is simple to use yet in some areas it lacks essential features. There is not enough options
	for questions when creating the survey, this is a important feature which I believe limits the site in some regards. Then to add to that the actual survey analysis tools are very lackluster and mostly locked 
	behind a pay wall. To summarize survey planet is a good site with a decent design flow and is great to use, yet it lacks many key features which I believe limits the usefulness of the site to some degree 
	I would say survey planet is a well designed site lacking in usable features. </p>
	
	<h3>Key things I learned from this analysis</h3>
	<ul>
	    <li>Make the site simple and well designed</li>
	    <li>Make the sending of the surveys simple</li>
	    <li>Try and include lots of usable features and question types</li>
	    <li>Make the data analysis very useful so the creator of the survey can sample useful data</li>
	</ul>
	
	<h3>Conclusion of analysis</h3>
	
	<p>After looking at Google Forms,Survey Monkey and Survey Planet I can say the site I liked the least out of all of them was Survey monkey this was due to terrible site design,ease of use and presentation. Yet
	even with that in mind Survey Monkey had many features Google Forms and Survey Planet lacked this stood out clearly showing that Survey Monkey had better survey design options and data analysis features. When it comes to design
	and ease of use Google Forms flowed better as a site yet I think survey planet is actually more usable by quite some margin its just simpler and faster when it comes to survey creation. Then again I have not
	gotten to Google Forms major problem yet it's a part of the Google Suite which means you need a Google account to use it, which could isolate users who do not have a Google Account. In terms of survey features
	and analytics Survey Monkey has by far the most features followed by Google forms, which brings to prominence the key Issue with Survey Planet it lacks features in comparison to all of the other sites.
	After thinking and analyzing all of the sites the site I most like is Google forms its easy to use and has the right amount of features there is only one real issue with the site and that is the sign up.
	Yet I would say the key thing I have learned is that all of these sites have there good points and flaws, I believe a incorporation of the design and the features of some of these sites would allow for the
	creation of a great site and I will use what I have learned to strive for and hopefully achieve that.</p>
_END;
}

// finish off the HTML for this page:
require_once "footer.php";
?>