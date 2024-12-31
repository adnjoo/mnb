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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Puppeteer Screenshot</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Website Screenshot Generator</h1>
        <form method="post" class="mb-4">
            <div class="mb-3">
                <label for="url" class="form-label">Enter a URL:</label>
                <input type="text" name="url" id="url" class="form-control" placeholder="https://example.com" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Generate Screenshot</button>
        </form>

        <?php if (isset($screenshotBase64)): ?>
            <div class="alert alert-success" role="alert">
                Screenshot generated successfully!
            </div>
            <h2 class="text-center">Screenshot</h2>
            <div class="text-center">
                <img src="<?php echo $screenshotBase64; ?>" alt="Website Screenshot" class="img-fluid mt-3">
            </div>
        <?php elseif (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+3oQb1DkzP3Dzo5JHXd7xNN2Brg2e" crossorigin="anonymous"></script>
</body>
</html>
