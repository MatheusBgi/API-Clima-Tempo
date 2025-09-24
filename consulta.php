<?php
// Para mais detalhes e personalizações acessem https://console.hgbrasil.com/documentation/weather

$ApiOnline = 0;
$woeid = "458706"; // Encontre o WOEID da sua cidade em https://console.hgbrasil.com/documentation/weather/tools
$key = "2c1691e0"; // Para criar sua KEY, basta criar sua conta e escolher o plano gratuito. https://console.hgbrasil.com/keys

$url = "https://api.hgbrasil.com/weather?woeid={$woeid}&format=json&key={$key}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // se precisar ignorar SSL
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$dados = json_decode($response, true);

if ($httpCode === 200 && isset($dados['results'])) {
  $ApiOnline = 1;
}?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Clima</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .forecast-day {
      flex: 1;
      text-align: center;
      padding: 15px; 
      border-right: 1px solid #333;
    }
    .forecast-day:last-child {
      border-right: none;
    }
    .forecast-day i {
      font-size: 2rem;
      color: #f9c74f;
    }
    
  </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
  <div class="weather-card shadow-lg p-3" style="max-width: 700px; width: 100%;">
    <?php if($ApiOnline){?>
      <div class="d-flex justify-content-between align-items-center border-bottom border-dark pb-3 mb-3">
        <div>
          <h5 class="fw-bold mb-1"><?=$dados['results']['city']?></h5>
          <ul class="list-unstyled text-start small">
            <li class="mb-2"><i class="bi bi-sunrise text-warning me-2"></i> Nascer do Sol: <strong><?=$dados['results']['sunrise']?></strong></li>
            <li class="mb-2"><i class="bi bi-sunset text-danger me-2"></i> Pôr do Sol: <strong><?=$dados['results']['sunset']?></strong></li>
            <li><i class="bi bi-wind text-info me-2"></i> Velocidade do vento: <strong><?=$dados['results']['wind_speedy']?></strong></li>
          </ul>
        </div>
        <div class="text-center">
          <img src="imagens/<?php echo $dados['results']['img_id']; ?>.png" class="imagem-do-tempo">
        </div>
        <div class="text-end">
          <h3 class="mb-1"><?php echo $dados['results']['temp']; ?> ºC</h3>
          <small><?php echo $dados['results']['description']; ?></small>
        </div>
      </div>
      <?php if (!empty($dados['results']['forecast']) && is_array($dados['results']['forecast'])) {?>
        <div class="d-flex">
          <?php foreach (array_slice($dados['results']['forecast'], 0, 5) as $proximos) {?>
              <div class="forecast-day">
                <p class="mb-1 fw-semibold"><?=$proximos['weekday']?> - <?=$proximos['date']?></p>
                <p class="mb-0">Max: <?=$proximos['max']?> °C</p>
                <p class="small">Min: <?=$proximos['min']?> °C</p>
                <small><?=$proximos['description']?></small>
              </div>
          <?php }?>
        </div>
      <?php }
    }else{?>
      <div class="text-center">
          <small>Não foi possível realizar sua consulta, tente novamente em alguns instantes.</small>
        </div>
    <?php }?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>