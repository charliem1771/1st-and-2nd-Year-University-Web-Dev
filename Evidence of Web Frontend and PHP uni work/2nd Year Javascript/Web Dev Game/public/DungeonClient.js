/*
 * These three variables hold information about the dungeon, received from the server
 * via the "dungeon data" message. Until the first message is received, they are
 * initialised to empty objects.
 *
 * - dungeon, an object, containing the following variables:
 * -- maze: a 2D array of integers, with the following numbers:
 * --- 0: wall
 * --- 1: corridor
 * --- 2+: numbered rooms, with 2 being the first room generated, 3 being the next, etc.
 * -- h: the height of the dungeon (y dimension)
 * -- w: the width of the dungeon (x dimension)
 * -- rooms: an array of objects describing the rooms in the dungeon, each object contains:
 * --- id: the integer representing this room in the dungeon (e.g. 2 for the first room)
 * --- h: the height of the room (y dimension)
 * --- w: the width of the room (x dimension)
 * --- x: the x coordinate of the top-left corner of the room
 * --- y: the y coordinate of the top-left corner of the room
 * --- cx: the x coordinate of the centre of the room
 * --- cy: the y coordinate of the centre of the room
 * -- roomSize: the average size of the rooms (as used when generating the dungeon)
 * -- _lastRoomId: the id of the next room to be generated (so _lastRoomId-1 is the last room in the dungeon)
 *
 * - dungeonStart
 * -- x, the row at which players should start in the dungeon
 * -- y, the column at which players should start in the dungeon
 *
 * - dungeonEnd
 * -- x, the row where the goal space of the dungeon is located
 * -- y, the column where the goal space of the dungeon  is located
 */
let dungeon = {};
let dungeonStart = {};
let dungeonEnd = {};

// load a spritesheet (dungeon_tiles.png) which holds the tiles
// we will use to draw the dungeon
// Art by MrBeast. Commissioned by OpenGameArt.org (http://opengameart.org)
const tilesImage = new Image();
tilesImage.src = "dungeon_tiles.png";

//Grabbing the 4 images which will be used for animation
const image_One = new Image();
image_One.src = "tile000.png";
const image_Two = new Image();
image_Two.src = "tile001.png";
const image_Three = new Image();
image_Three.src = "tile002.png";
const image_Four = new Image();
image_Four.src = "tile003.png";

/* 
 * Establish a connection to our server
 * We will need to reuse the 'socket' variable to both send messages
 * and receive them, by way of adding event handlers for the various
 * messages we expect to receive
 *
 * Replace localhost with a specific URL or IP address if testing
 * across multiple computers
 *
 * See Real-Time Servers III: socket.io and Messaging for help understanding how
 * we set up and use socket.io
 */
const socket = io.connect("http://localhost:8081");

/*
 * This is the event handler for the 'dungeon data' message
 * When a 'dungeon data' message is received from the server, this block of code executes
 * 
 * The server is sending us either initial information about a dungeon, or,
 * updated information about a dungeon, and so we want to replace our existing
 * dungeon variables with the new information.
 *
 * We know the specification of the information we receive (from the documentation
 * and design of the server), and use this to help write this handler.
 */
socket.on("dungeon data", function (data) 
{
    dungeon = data.dungeon;
    dungeonStart = data.startingPoint;
    dungeonEnd = data.endingPoint;
});

/*
 * The identifySpaceType function takes an x, y coordinate within the dungeon and identifies
 * which type of tile needs to be drawn, based on which directions it is possible
 * to move to from this space. For example, a tile from which a player can move up
 * or right from needs to have walls on the bottom and left.
 *
 * Once a tile type has been identified, the necessary details to draw this
 * tile are returned from this method. Those details specifically are:
 * - tilesetX: the x coordinate, in pixels, within the spritesheet (dungeon_tiles.png) of the top left of the tile
 * - tilesetY: the y coordinate, in pixels, within the spritesheet (dungeon_tiles.png) of the top left of the tile
 * - tilesizeX: the width of the tile
 * - tilesizeY: the height of the tile
 */
function identifySpaceType(x, y) 
{
    let returnObject = 
    {
        spaceType: "",
        tilesetX: 0,
        tilesetY: 0,
        tilesizeX: 16,
        tilesizeY: 16
    };

    let canMoveUp = false;
    let canMoveLeft = false;
    let canMoveRight = false;
    let canMoveDown = false;

    // check for out of bounds (i.e. this move would move the player off the edge,
    // which also saves us from checking out of bounds of the array) and, if not
    // out of bounds, check if the space can be moved to (i.e. contains a corridor/room)
    if (x - 1 >= 0 && dungeon.maze[y][x - 1] > 0) 
    {
        canMoveLeft = true;
    }
    if (x + 1 < dungeon.w && dungeon.maze[y][x + 1] > 0) 
    {
        canMoveRight = true;
    }
    if (y - 1 >= 0 && dungeon.maze[y - 1][x] > 0) 
    {
        canMoveUp = true;
    }
    if (y + 1 < dungeon.h && dungeon.maze[y + 1][x] > 0) 
    {
        canMoveDown = true;
    }

    if (canMoveUp && canMoveRight && canMoveDown && canMoveLeft) 
    {
        returnObject.spaceType = "all_exits";
        returnObject.tilesetX = 16;
        returnObject.tilesetY = 16;
    }
    else if (canMoveUp && canMoveRight && canMoveDown) 
    {
        returnObject.spaceType = "left_wall";
        returnObject.tilesetX = 0;
        returnObject.tilesetY = 16;
    }
    else if (canMoveRight && canMoveDown && canMoveLeft) 
    {
        returnObject.spaceType = "up_wall";
        returnObject.tilesetX = 16;
        returnObject.tilesetY = 0;
    }
    else if (canMoveDown && canMoveLeft && canMoveUp) 
    {
        returnObject.spaceType = "right_wall";
        returnObject.tilesetX = 32;
        returnObject.tilesetY = 16;
    }
    else if (canMoveLeft && canMoveUp && canMoveRight) 
    {
        returnObject.spaceType = "down_wall";
        returnObject.tilesetX = 16;
        returnObject.tilesetY = 32;
    }
    else if (canMoveUp && canMoveDown) 
    {
        returnObject.spaceType = "vertical_corridor";
        returnObject.tilesetX = 144;
        returnObject.tilesetY = 16;
    }
    else if (canMoveLeft && canMoveRight) 
    {
        returnObject.spaceType = "horizontal_corridor";
        returnObject.tilesetX = 112;
        returnObject.tilesetY = 32;
    }
    else if (canMoveUp && canMoveLeft) 
    {
        returnObject.spaceType = "bottom_right";
        returnObject.tilesetX = 32;
        returnObject.tilesetY = 32;
    }
    else if (canMoveUp && canMoveRight) 
    {
        returnObject.spaceType = "bottom_left";
        returnObject.tilesetX = 0;
        returnObject.tilesetY = 32;
    }
    else if (canMoveDown && canMoveLeft) 
    {
        returnObject.spaceType = "top_right";
        returnObject.tilesetX = 32;
        returnObject.tilesetY = 0;
    }
    else if (canMoveDown && canMoveRight) 
    {
        returnObject.spaceType = "top_left";
        returnObject.tilesetX = 0;
        returnObject.tilesetY = 0;
    }
    return returnObject;
}

/*
 * Once our page is fully loaded and ready, we call startAnimating
 * to kick off our animation loop.
 * We pass in a value - our fps - to control the speed of our animation.
 */
 


//A variable to store the ID for the client
let client_id;
//A array of ever single client
let player_client_array = [];
//The i and j positons which are unaffected by the server they are used to allow a red square to uniquely identify the player your using 
let i = 3.2;
let j = 2.5;
let cells = 20;
//The counter is used to iterate through the 4 images 
let counter = 0;
let fpsInterval;
let then;

//when the player_on_client function is called
socket.on("player_on_client",function(data)
{
    //setting the player_client_array to equal the players_server array
	player_client_array = data;
});
//when the update position function is called
socket.on("update position",function(data)
{
    player_client_array = data;
});
//when the clientside_id function is called
socket.on("clientside_id",function(data)
{
    //setting the client_id to be equal to the id stored in the players object
    client_id = data;
});

$(document).ready(function () 
{
	startAnimating(60);
     
    //grabbing the gameCanvas for the touch movement
    var gameElement = document.getElementById('gameCanvas');
    //creating a hammer object which is used to control touch movement
	var touchMovement = new Hammer(gameElement);
	//Setting the hammer object called touchMovement so it can pan in all directions
    touchMovement.get('pan').set({direction:Hammer.DIRECTION_ALL});
    
    //when the user pans right
	touchMovement.on("panright",function(event)
	{
        //Making a for loop iterate through the length of the player_client_array
        for(let n = 0; n < player_client_array.length; n ++)
		{
            //checking that the socket.id of the client being used matches a id from the server socket ids.
			if(client_id == player_client_array[n].id)
			{
                console.log("touch test");
                //checking if the player can move right
                if (player_client_array[n].i + 1 < dungeon.w && dungeon.maze[player_client_array[n].j][player_client_array[n].i + 1] > 0) 
				{
                    //updating the right facing position of this client by a increment of 1 for as long as the user pans right
                    player_client_array[n].i ++;
                    //updating the red square on this client in the same direction as the player sprite
                    i ++;
                    console.log("touch test");
                }
                //emitting the updates to the client array back to the server
                socket.emit("update server",player_client_array);
            }
        }
	});
    //The 3 touch movement functions all work the same as the one explained above
    //The only difference is they each move in there respective direction using the same method
	touchMovement.on("panleft",function(event)
	{
		for(let n = 0; n < player_client_array.length; n ++)
		{
			if(client_id == player_client_array[n].id)
			{
                if (player_client_array[n].i - 1 >= 0 && dungeon.maze[player_client_array[n].j][player_client_array[n].i -  1] > 0) 
				{
                    player_client_array[n].i --;
                    i --;
                }
                socket.emit("update server",player_client_array);
            }
        }
	});
	
	touchMovement.on("pandown",function(event)
	{
        for(let n = 0; n < player_client_array.length; n ++)
		{
			if(client_id == player_client_array[n].id)
			{
                if (player_client_array[n].j + 1 < dungeon.h && dungeon.maze[player_client_array[n].j + 1][player_client_array[n].i] > 0) 
				{
                    player_client_array[n].j ++;
                    j ++;
                }
                socket.emit("update server",player_client_array);
            }
        }
	});
	
	touchMovement.on("panup",function(event)
	{
		for(let n = 0; n < player_client_array.length; n ++)
		{
			if(client_id == player_client_array[n].id)
			{
                if (player_client_array[n].j - 1 >= 0 && dungeon.maze[player_client_array[n].j - 1][player_client_array[n].i] > 0) 
				{
                    player_client_array[n].j --;
                    j --;
                }
                socket.emit("update server",player_client_array);
            }
        }	
    });
    //Grabbing a button with the id moveUp
    $("#moveUp").click(function()
    {
        //the code here works exactly like the code in the touch functionality check that for a explanation as to what is happening
        //It is repeated 3 more times for the rest of the button click functionality
        for(let n = 0; n < player_client_array.length; n ++)
		{
			if(client_id == player_client_array[n].id)
			{
                if (player_client_array[n].j - 1 >= 0 && dungeon.maze[player_client_array[n].j - 1][player_client_array[n].i] > 0) 
				{
                    player_client_array[n].j --;
                    j --;
                }
                socket.emit("update server",player_client_array);
            }
        }
    });
    //Grabbing a button with the id moveDown
    $("#moveDown").click(function()
    {
        for(let n = 0; n < player_client_array.length; n ++)
		{
			if(client_id == player_client_array[n].id)
			{
                if (player_client_array[n].j + 1 < dungeon.h && dungeon.maze[player_client_array[n].j + 1][player_client_array[n].i] > 0) 
				{
                    player_client_array[n].j ++;
                    j ++;
                }
                socket.emit("update server",player_client_array);
            }   
        }
    });
    //Grabbing a button with the id moveRight
    $("#moveRight").click(function()
    {
        for(let n = 0; n < player_client_array.length; n ++)
		{
			if(client_id == player_client_array[n].id)
			{
                if (player_client_array[n].i + 1 < dungeon.w && dungeon.maze[player_client_array[n].j][player_client_array[n].i + 1] > 0) 
				{
                    player_client_array[n].i ++;
                    i ++;
                }
            }
        }
    });
    //Grabbing a button with the id moveLeft
	$("#moveLeft").click(function()
    {
        for(let n = 0; n < player_client_array.length; n ++)
		{
			if(client_id == player_client_array[n].id)
			{
                if (player_client_array[n].i - 1 >= 0 && dungeon.maze[player_client_array[n].j][player_client_array[n].i -  1] > 0) 
				{
                    player_client_array[n].i --;
                    i --;
                }
            }
        }
    });
    //grabbing the body element and setting a keydown function to it
    $("body").keydown(function(event)
	{
        //variables used to help with bounds checks
        let moveUp = false;
        let moveLeft = false;
        let moveRight = false;
        let moveDown = false;
		
    

        //for loop to iterate through the player_client_array
		for(let n = 0; n < player_client_array.length; n ++)
		{
            //checking if the current client_id matches any of the id's stored on the server
			if(client_id == player_client_array[n].id)
			{
                /*Doing bounds checks for all 4 directions the player can go in if the boolean is true the player can move that way
                You can see that i use the i and j values from the array to run these checks this is to make sure the bounds checks
                still work with server side intergration*/
				if (player_client_array[n].i - 1 >= 0 && dungeon.maze[player_client_array[n].j][player_client_array[n].i -  1] > 0) 
				{
                    /*If the players not colliding with any left facing walls then moveLeft is equal to true
                    the same logic applies to all 3 other if statements used to check for collision with the maze*/
					moveLeft = true;
				}
				if (player_client_array[n].i + 1 < dungeon.w && dungeon.maze[player_client_array[n].j][player_client_array[n].i + 1] > 0) 
				{
					moveRight = true;
				}
				if (player_client_array[n].j - 1 >= 0 && dungeon.maze[player_client_array[n].j - 1][player_client_array[n].i] > 0) 
				{
					moveUp = true;
				}
				if (player_client_array[n].j + 1 < dungeon.h && dungeon.maze[player_client_array[n].j + 1][player_client_array[n].i] > 0) 
				{
					moveDown = true;
				}
                
                //checking if the player is pressing the right arrow key and moveRight is equal to true
				if(event.which == 39 && moveRight == true)
				{
                    //moving the player right 
                    player_client_array[n].i ++;
                    //moving the red square right
                    i ++;
				}//the above code is repeated 3 times for the other directions
				else if(event.which == 40 && moveDown == true) 
				{
                    player_client_array[n].j ++;
                    j++;
				}
				else if(event.which == 37 && moveLeft == true)
				{
                    player_client_array[n].i --;
                    i --;
				}
				else if(event.which == 38 && moveUp == true)
				{
                    player_client_array[n].j --;
                    j --;
                }
                //before exiting the for loop to check if the id of the client matches a id on the server update the server array so
                //so only one client moves but every other client sees it
                socket.emit("update server",player_client_array);
			}
        }
    });
});


function startAnimating(fps) 
{
    fpsInterval = 1000 / fps;
    then = Date.now();
    animate();
}
/*
 * The animate function is called repeatedly using requestAnimationFrame (see Games on the Web I - HTML5 Graphics and Animations).
 */

function animate() 
{
    requestAnimationFrame(animate);
    let now = Date.now();
    let elapsed = now - then;
	//console.log("Number of ids: "+player_client_array.length);
    if (elapsed > fpsInterval) 
    {
        then = now - (elapsed % fpsInterval);
        // Acquire both a canvas (using jQuery) and its associated context
        let canvas = $("canvas").get(0);
        let context = canvas.getContext("2d");

        // Calculate the width and height of each cell in our dungeon
        // by diving the pixel width/height of the canvas by the number of
        // cells in the dungeon
        let cellWidth = canvas.width / dungeon.w;
        let cellHeight = canvas.height / dungeon.h;

        // Clear the drawing area each animation cycle
        context.clearRect(0, 0, canvas.width, canvas.height);

        /* We check each one of our tiles within the dungeon using a nested for loop
         * which runs from 0 to the width of the dungeon in the x dimension
         * and from 0 to the height of the dungeon in the y dimension
         *
         * For each space in the dungeon, we check whether it is a space that can be
         * moved into (i.e. it isn't a 0 in the 2D array), and if so, we use the identifySpaceType
         * method to check which tile needs to be drawn.
         *
         * This returns an object containing the information required to draw a subset of the
         * tilesImage as appropriate for that tile.
         * See: https://developer.mozilla.org/en-US/docs/Web/API/CanvasRenderingContext2D/drawImage
         * to remind yourself how the drawImage method works.
         */
        for (let x = 0; x < dungeon.w; x++) 
        {
            for (let y = 0; y < dungeon.h; y++) 
            {
                if (dungeon.maze[y][x] > 0) 
                {
                    let tileInformation = identifySpaceType(x, y);
                    context.drawImage(tilesImage,
                        tileInformation.tilesetX,
                        tileInformation.tilesetY,
                        tileInformation.tilesizeX,
                        tileInformation.tilesizeY,
                        x * cellWidth,
                        y * cellHeight,
                        cellWidth,
                        cellHeight);
                } 
                else 
                {
                    context.fillStyle = "black";
                    context.fillRect(
                        x * cellWidth,
                        y * cellHeight,
                        cellWidth,
                        cellHeight
                    );
                }
            }
        }

        // The start point is calculated by multiplying the cell location (dungeonStart.x, dungeonStart.y)
        // by the cellWidth and cellHeight respectively
        // Refer to: Games on the Web I - HTML5 Graphics and Animations, Lab Exercise 2
        context.drawImage(tilesImage,
            16, 80, 16, 16,
            dungeonStart.x * cellWidth,
            dungeonStart.y * cellHeight,
            cellWidth,
            cellHeight);

        // The goal is calculated by multiplying the cell location (dungeonEnd.x, dungeonEnd.y)
        // by the cellWidth and cellHeight respectively
        // Refer to: Games on the Web I - HTML5 Graphics and Animations, Lab Exercise 2
        context.drawImage(tilesImage,
            224, 80, 16, 16,
            dungeonEnd.x * cellWidth,
            dungeonEnd.y * cellHeight,
            cellWidth,
            cellHeight);

            //continually increase the counter by 1
            counter ++;
            //using a for loop in the same way I did 3 times before
			for(var n = 0; n < player_client_array.length; n++)
			{	
                //if the counter is below 20 render the first image
                if(counter > 0 && counter < 20)
                {
                    //use the i and j positions stored in the array to draw the sprites for the players, so when they are increased in
                    //the movement functions the sprites move
                    context.drawImage(image_One,player_client_array[n].i*(canvas.width/cells),
                    player_client_array[n].j*(canvas.height/cells),
                        (canvas.width/cells),
                        (canvas.height/cells));	
                }	
                //if the counter is below 40 render the second image
                else if(counter > 20 && counter < 40)
                {
                    context.drawImage(image_Two,player_client_array[n].i*(canvas.width/cells),
                    player_client_array[n].j*(canvas.height/cells),
                        (canvas.width/cells),
                        (canvas.height/cells));
                }
                //if the counter is below 60 render the third image
                else if(counter > 40 && counter < 60)
                {
                    context.drawImage(image_Three,player_client_array[n].i*(canvas.width/cells),
                    player_client_array[n].j*(canvas.height/cells),
                        (canvas.width/cells),
                        (canvas.height/cells));
                }
                //if the counter is below 80 render the fourth image
                else if(counter > 60 && counter <= 80)
                {
                    context.drawImage(image_Four,player_client_array[n].i*(canvas.width/cells),
                    player_client_array[n].j*(canvas.height/cells),
                        (canvas.width/cells),
                        (canvas.height/cells));	        
                }
                //if the counter equals 80 reset it to 0 so the sprite loop keeps going
                if(counter == 80 )
                {
                    counter = 0;
                }
                //set fill style to red
                context.fillStyle = "#FF0000";
                //draw the red rectangle with values that are not synced to the server so it only appears on the users client
                context.fillRect(i*(canvas.width/cells),j*(canvas.height/cells),10,10);
                
                //checking if the player_client_array i and j values of any connected client is equal the end x and y values of the dungeon
                if(player_client_array[n].i == dungeonEnd.x && player_client_array[n].j == dungeonEnd.y)
                {
                    //setting the players i positions to the dungeonStart.x
                    player_client_array[n].i = dungeonStart.x;
                    //setting the players j positions to the dungeonStart.y
                    player_client_array[n].j = dungeonStart.y;
                    //resesting the dungein
                    socket.emit("reset dungeon",true);
                    //setting the red rectangles i and j positon back to the start of the server
                    i = dungeonStart.x;
                    j = dungeonStart.y - 0.5;
                    //updating the server
                    socket.emit("update server",player_client_array);
                }
            }
        }
}

