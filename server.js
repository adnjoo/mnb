const express = require("express");
const puppeteer = require("puppeteer");

const app = express();
app.use(express.json());

app.get("/screenshot", async (req, res) => {
    const { url } = req.query;

    if (!url) {
        return res.status(400).send({ error: "URL is required" });
    }

    try {
        const browser = await puppeteer.launch();
        const page = await browser.newPage();
        await page.goto(url, { waitUntil: "networkidle2" });

        const screenshot = await page.screenshot({ encoding: "base64" });
        await browser.close();

        res.send({ screenshot: `data:image/png;base64,${screenshot}` });
    } catch (err) {
        res.status(500).send({ error: "Failed to take screenshot", details: err.message });
    }
});

const PORT = 3000;
app.listen(PORT, () => {
    console.log(`Puppeteer service running on http://localhost:${PORT}`);
});
