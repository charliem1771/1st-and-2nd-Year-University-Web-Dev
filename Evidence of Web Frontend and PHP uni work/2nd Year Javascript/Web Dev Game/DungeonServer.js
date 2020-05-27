// See Real-Time Servers II: File Servers for understanding 
// how we set up and use express
const express = require("express");
const app = express();
const server = require("http").Server(app);
const io = require("socket.io")(server);

// We will use the dungeongenerator module to generate random dungeons
// Details at: https://www.npmjs.com/package/dungeongenerator
// Source at: https://github.com/nerox8664/dungeongenerator
const DungeonGenerator = require("dungeongenerator");

// We are going to serve our static pages from the public directory
// See Real-Time Servers II: File Servers for understanding
// how we set up and use express
app.use(express.static("public"));

/*  These variables store information about the dungeon that we will later
 *  send to clients. In particular:
 *  - the dungeonStart variable will store the x and y coordinates of the start point of the dungeon
 *  - the dungeonEnd variable will store the x and y coordinates of the end point of the dungeon
 *  - the dungeonOptions object contains four variables, which describe the default state of the dungeon:
 *  - - dungeon_width: the width of the dungeon (size in the x dimension)
 *  - - dungeon_height: the height of the dungeon (size in the y dimension)
 *  - - number_of_rooms: the approximate number of rooms to generate
 *  - - average_room_size: roughly how big the rooms will be (in terms of both height and width)
 *  - this object is passed to the dungeon constructor in the generateDungeon function
 */
let dungeon = {};
let dungeonStart = {};
let dungeonEnd = {};
const dungeonOptions = {
    dungeon_width: 20,
    dungeon_height: 20,
    number_of_rooms: 7,
    average_room_size: 8
};

/*
 * The getDungeonData function packages up important information about a dungeon
 * into an object and prepares it for sending in a message. 
 *
 * The members of the returned object are as follows:
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
 * - startingPoint
 * -- x: the column at which players should start in the dungeon
 * -- y: the row at which players should start in the dungeon
 *
 * - endingPoint
 * -- x: the column where the goal space of the dungeon is located
 * -- y: the row where the goal space of the dungeon is located
 *
 */
function getDungeonData() 
{
    return {
        dungeon,
        startingPoint: dungeonStart,
        endingPoint: dungeonEnd
    };
}

/*
 * This is our event handler for a connection.
 * That is to say, any code written here executes when a client makes a connection to the server
 * (i.e. when the page is loaded)
 * 
 * See Real-Time Servers III: socket.io and Messaging for help understanding how
 * we set up and use socket.io
 */

//declare players object
let players = {};
//declare players array
let players_server = [];

io.on("connection", function (socket) 
{
	console.log("A player has connected");
	//Populating the players object with values once connected
	players = 
	{
		i: 3, //assigning the i start point
		j: 3, //assigning the j start point
		id: socket.id //assigning the socket id
    }
    //adding the players object to the player_server array
    players_server.push(players);
    //emitting the players_server array to the player_on_client function 
    io.sockets.emit('player_on_client', players_server);
    
    //Running the update server function when it is called
	socket.on("update server",function(data)
	{
        //setting the player_server array to equal the data stored in the player_client_array
        players_server = data;
        //emitting the new updated players_server array to the clients
		io.sockets.emit("update position",players_server);
	});
    //emitting the client players.id to the client
    socket.emit("clientside_id",players.id);
    socket.emit("dungeon data", getDungeonData());
    
    //used when dungeon reset is called
	socket.on("reset dungeon", function (data) 
	{
        //emitting the new dungeon data back to the client
        io.sockets.emit("dungeon data", getDungeonData());
        //generating a new dungeon
		generateDungeon();
		console.log("generated new dungeon");
	});
	//when the player leaves the server
	socket.on('disconnect', function() 
	{
        //says the user has disconnected
        console.log('user disconnected');
	});
});

/*
 * This method locates a specific room, based on a given index, and retrieves the
 * centre point, and returns this as an object with an x and y variable.
 * For example, this method given the integer 2, would return an object
 * with an x and y indicating the centre point of the room with an id of 2.
 */
function getCenterPositionOfSpecificRoom(roomIndex) 
{
    let position = 
    {
        x: 0,
        y: 0
    };

    for (let i = 0; i < dungeon.rooms.length; i++) 
    {
        let room = dungeon.rooms[i];
        if (room.id === roomIndex) 
		{
            position.x = room.cx;
            position.y = room.cy;
            return position;
        }
    }
    return position;
}

/*
 * The generateDungeon function uses the dungeongenerator module to create a random dungeon,
 * which is stored in the 'dungeon' variable.
 *
 * Additionally, we find a start point (this is always the centre point of the first generated room)
 * and an end point is located (this is always the centre point of the last generated room).
 */
function generateDungeon() 
{
    dungeon = new DungeonGenerator(
        dungeonOptions.dungeon_height,
        dungeonOptions.dungeon_width,
        dungeonOptions.number_of_rooms,
        dungeonOptions.average_room_size
    );
    console.log(dungeon);
    dungeonStart = getCenterPositionOfSpecificRoom(2);
    dungeonEnd = getCenterPositionOfSpecificRoom(dungeon._lastRoomId - 1);
}

/*
 * Start the server, listening on port 8081.
 * Once the server has started, output confirmation to the server's console.
 * After initial startup, generate a dungeon, ready for the first time a client connects.
 *
 */
server.listen(8081, function () 
{
    console.log("Dungeon server has started - connect to http://localhost:8081");
    generateDungeon();
    console.log("Initial dungeon generated!");
});