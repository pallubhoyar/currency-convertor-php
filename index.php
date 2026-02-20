<?php
$result = "";
$error = "";
$amount = "";
$from = "USD";
$to = "INR";

$apiKey = "6f9dd7e528d577dd3108652f"; 

$currencies = [
"USD","INR","EUR","GBP","AUD","CAD","SGD","JPY","CNY","CHF",
"NZD","AED","SAR","PKR","BDT","LKR","THB","MYR","ZAR","RUB",
"BRL","MXN","KRW","HKD","SEK","NOK","DKK","PLN","TRY"
];

if(isset($_POST['swap'])){
    $amount = $_POST['amount'];
    $from = $_POST['to'];
    $to = $_POST['from'];
}

if(isset($_POST['convert'])){
    $amount = floatval($_POST['amount']);
    $from = $_POST['from'];
    $to = $_POST['to'];

    if($amount > 0){

        $url = "https://v6.exchangerate-api.com/v6/$apiKey/latest/$from";

        $response = @file_get_contents($url);

        if($response !== FALSE){

            $data = json_decode($response, true);

            if($data["result"] == "success" && isset($data["conversion_rates"][$to])){

                $rate = $data["conversion_rates"][$to];
                $converted = $amount * $rate;
                $result = number_format($converted, 2);

            } else {
                $error = "Conversion failed. Check API key.";
            }

        } else {
            $error = "API connection error.";
        }

    } else {
        $error = "Enter valid amount.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Professional Currency Converter</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="card">

<h2>💹 Currency Converter</h2>

<form method="POST">

<input type="number" step="0.01" name="amount"
placeholder="Enter Amount"
required
value="<?php echo htmlspecialchars($amount); ?>">

<div class="row">

<select name="from">
<?php foreach($currencies as $code){ ?>
<option value="<?php echo $code; ?>"
<?php if($from==$code) echo "selected"; ?>>
<?php echo $code; ?>
</option>
<?php } ?>
</select>

<button type="submit" name="swap" class="swap">⇄</button>

<select name="to">
<?php foreach($currencies as $code){ ?>
<option value="<?php echo $code; ?>"
<?php if($to==$code) echo "selected"; ?>>
<?php echo $code; ?>
</option>
<?php } ?>
</select>

</div>

<button type="submit" name="convert" class="convert">
Convert Now
</button>

</form>

<?php if($result!=""){ ?>
<div class="result">
<?php echo "$amount $from = $result $to"; ?>
</div>
<?php } ?>

<?php if($error!=""){ ?>
<div class="error">
<?php echo $error; ?>
</div>
<?php } ?>

</div>

</body>
</html>