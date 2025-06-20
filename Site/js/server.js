const express = require("express");
const mysql = require("mysql2");
const cors = require("cors");
const bodyParser = require("body-parser");
const bcrypt = require("bcryptjs");

const app = express();
app.use(cors());
app.use(bodyParser.json());

const db = mysql.createConnection({
    host: "aws-0-eu-west-3.pooler.supabase.com", // Ex: "localhost" ou "seu-servidor.com"
    user: "postgres.kszhqvvmlrlkvsvbpinx",
    password: "LEVufRUwFPTdywIp",
    database: "postgres"
});

db.connect(err => {
    if (err) throw err;
    console.log("Conectado ao banco de dados!");
});

app.post("/login", (req, res) => {
    const { email, senha } = req.body;

    db.query("SELECT * FROM utilizadores WHERE email = ?", [email], (err, results) => {
        if (err) return res.status(500).json({ mensagem: "Erro no servidor." });

        if (results.length === 0) {
            return res.status(401).json({ mensagem: "Utilizador não encontrado." });
        }

        const usuario = results[0];
        const senhaCorreta = bcrypt.compareSync(senha, usuario.senha);

        if (!senhaCorreta) {
            return res.status(401).json({ mensagem: "Senha incorreta." });
        }

        res.json({ mensagem: "Login bem-sucedido!" });
    });
});

app.listen(3000, () => console.log("Servidor rodando na porta 3000"));
