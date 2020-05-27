const express = require('express');
const app = express();

app.use(express.static('htdocs'));
app.listen(8081);
console.log("We doing it boii");