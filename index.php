<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['url'];

    if (filter_var($url, FILTER_VALIDATE_URL)) {
        try {
            $client = new Client();
            $response = $client->post('http://localhost:3000/screenshot', [
                'json' => ['url' => $url],
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['screenshot'])) {
                $screenshotBase64 = $data['screenshot'];
            } else {
                $error = "Screenshot could not be generated.";
            }
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    } else {
        $error = "Invalid URL.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puppeteer Screenshot</title>
</head>
<body>
    <h1>Website Screenshot Generator (Puppeteer)</h1>
    <form method="post">
        <label for="url">Enter a URL:</label>
        <input type="text" name="url" id="url" required>
        <button type="submit">Generate Screenshot</button>
    </form>

    <?php if (isset($screenshotBase64)): ?>
        <h2>Screenshot</h2>
        <img src="<?php echo $screenshotBase64; ?>" alt="Website Screenshot" style="max-width: 100%;">
    <?php elseif (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
</body>
</html>
