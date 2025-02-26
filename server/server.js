const WebSocket = require('ws');
const { SerialPort } = require("serialport");
const { ReadlineParser } = require('@serialport/parser-readline'); 

// Corrigido: Definindo path no objeto de configuração
const port = new SerialPort({
    path: "COM4",  // Substitua "COM4" pela porta correta, caso necessário
    baudRate: 9600 // Definindo a taxa de comunicação
});

const parser = port.pipe(new ReadlineParser({ delimiter: "\n" })); 

// Criando o servidor WebSocket na porta 8080
const wss = new WebSocket.Server({ port: 8080 });

wss.on("connection", function(ws) {
    console.log("Cliente conectado");

    // Escutando os dados da porta serial
    parser.on("data", function(data) {
        console.log("RFID Lido:", data);
        ws.send(data.trim());  // Enviando os dados lidos para o cliente WebSocket
    });
});

console.log("Servidor WebSocket rodando na porta 8080");
