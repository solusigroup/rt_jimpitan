const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');
const http = require('http');
const url = require('url');

// 1. Inisialisasi WhatsApp Client dengan penyimpanan session lokal
const client = new Client({
    authStrategy: new LocalAuth(),
    puppeteer: {
        headless: true,
        args: [
            '--no-sandbox', 
            '--disable-setuid-sandbox',
            '--disable-dev-shm-usage',
            '--disable-accelerated-2d-canvas',
            '--no-first-run',
            '--no-zygote',
            '--single-process',
            '--disable-gpu'
        ]
    }
});

// Event ketika QR Code digenerate (Tampilkan di Terminal)
client.on('qr', (qr) => {
    console.log('\n==========================================================');
    console.log('=== SILAKAN SCAN QR CODE BERIKUT DENGAN WHATSAPP ANDA ===');
    console.log('==========================================================\n');
    qrcode.generate(qr, { small: true });
});

// Event ketika WhatsApp siap digunakan
client.on('ready', () => {
    console.log('\n==========================================================');
    console.log('=== WHATSAPP GATEWAY BERHASIL CONNECT & SIAP DIGUNAKAN ===');
    console.log('==========================================================\n');
});

client.on('auth_failure', (msg) => {
    console.error('=== GAGAL OTENTIKASI:', msg);
});

client.initialize();

// 2. Membuat Server HTTP API Lokal di Port 3000
const PORT = 3000;
const server = http.createServer(async (req, res) => {
    // Header CORS
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
    res.setHeader('Access-Control-Allow-Headers', 'Content-Type');

    if (req.method === 'OPTIONS') {
        res.writeHead(200);
        res.end();
        return;
    }

    const parsedUrl = url.parse(req.url, true);

    // Endpoint untuk mengirim pesan
    if (parsedUrl.pathname === '/send' && req.method === 'POST') {
        let body = '';
        req.on('data', chunk => {
            body += chunk.toString();
        });
        req.on('end', async () => {
            try {
                const data = JSON.parse(body);
                const { to, message } = data;

                if (!to || !message) {
                    res.writeHead(400, { 'Content-Type': 'application/json' });
                    res.end(JSON.stringify({ status: 'error', message: 'Parameter "to" dan "message" wajib diisi.' }));
                    return;
                }

                // Format nomor HP agar sesuai standar WhatsApp (mengganti 0 didepan menjadi 62)
                let formattedNumber = to.replace(/\D/g, '');
                if (formattedNumber.startsWith('0')) {
                    formattedNumber = '62' + formattedNumber.substr(1);
                }
                if (!formattedNumber.endsWith('@c.us')) {
                    formattedNumber = formattedNumber + '@c.us';
                }

                console.log(`[INFO] Mengirim pesan ke ${formattedNumber}...`);
                await client.sendMessage(formattedNumber, message);

                res.writeHead(200, { 'Content-Type': 'application/json' });
                res.end(JSON.stringify({ status: 'success', message: 'Pesan berhasil dikirim.' }));
            } catch (err) {
                console.error('[ERR] Gagal mengirim pesan:', err.message);
                res.writeHead(500, { 'Content-Type': 'application/json' });
                res.end(JSON.stringify({ status: 'error', message: err.message }));
            }
        });
    } else {
        res.writeHead(404, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ status: 'error', message: 'Endpoint tidak ditemukan.' }));
    }
});

server.listen(PORT, () => {
    console.log(`[HTTP] Local Server berjalan di http://localhost:${PORT}`);
});
