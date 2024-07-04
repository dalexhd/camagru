"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const http_1 = __importDefault(require("http"));
const port = 5000;
const server = http_1.default.createServer((req, res) => {
    if (req.url === '/api/data' && req.method === 'GET') {
        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ message: 'Hello from the server!' }));
    }
    if (req.url === '/api/me' && req.method === 'GET') {
        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ isLoggedIn: false, userName: 'aborboll' }));
    }
});
server.listen(port, () => {
    console.log(`Server is running on http://localhost:${port}`);
});
