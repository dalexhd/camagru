import http from 'http';

const port = 5000;

const server = http.createServer((req, res) => {
  if (req.url === '/api/data' && req.method === 'GET') {
    res.writeHead(200, { 'Content-Type': 'application/json' });
    res.end(JSON.stringify({ message: 'Hello from the server!' }));
  }
});

server.listen(port, () => {
  console.log(`Server is running on http://localhost:${port}`);
});
