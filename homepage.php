<!DOCTYPE html>
<html>
<head>
<title>Bean & Bear Coffee Shop</title>

<style>

body{
margin:0;
font-family:Georgia, serif;
background-image:url('images/bg2.jpg');
background-size:cover;
background-position:center;
height:100vh;
color:#6f4e37;
}


.hero{
height:90vh;
display:flex;
flex-direction:column;
justify-content:center;
align-items:center;
text-align:center;
}

.hero img{
width:230px;
margin-bottom:15px;
}

.hero h1{
font-size:70px;
margin:10px;
text-shadow:2px 2px 10px rgba(0,0,0,0.5);
}

.hero p{
font-size:22px;
max-width:600px;
}

.hero button{
margin-top:30px;
padding:14px 30px;
font-size:18px;
border:none;
border-radius:8px;
background:#6f4e37;
color:white;
cursor:pointer;
font-family:Georgia, serif;
transition:0.3s;
}

.hero button:hover{
background:#5a3e2b;
}


/* MOBILE */

@media(max-width:700px){

.hero h1{
font-size:45px;
}

.hero p{
font-size:18px;
}

}

</style>

</head>

<body>



<!-- LANDING PAGE -->

<div class="hero">

<img src="images/bearLogo.png">

<h1>Bean & Bear</h1>

<p>
Welcome to Bean & Bear Coffee Shop.  
Order your favorite coffee online and enjoy the perfect brew anytime.
</p>

<a href="login.php">
<button>Order Now</button>
</a>

</div>



</body>
</html>
```
