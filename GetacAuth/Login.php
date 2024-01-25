<!--用Getac帳號登入，取得Token-->
<!DOCTYPE html>
<head>
    <style>
        h3{
            font-size: 30px;
            text-align: center;
        }
        div{
            text-align: center;
        }
    </style>
    <meta charset="UTF-8">
    <title>LOGIN</title>
</head>
<body>
    <h3>登入PMIS</h3>
    <form method="get" action="https://sso.getac.com/Auth/SignIn">
    <input name="client_id" type="hidden" value="mOxMrVbwWBry3oeTrYOQOIJnRYT5AOT8" />
    <input name="redirect_uri" type="hidden" value="https://gtc-id.getac.com:4433/redirecturi.php" />
	Enter Your Email:<input type="text" name="login_hint" >
	<input type="submit" value="Submit">
</body>
<!--change-->
</html>