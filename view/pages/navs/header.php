<header>
	<nav class="navbar">
		<div class="container">
			<a class="navbar-brand" href="./">
				<img src="view/assets/images/logo.png" alt="Logo" class="logo">
			</a>
			<button class="navbar-toggler boton-sombra" type="button" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon one"></span>
				<span class="navbar-toggler-icon two"></span>
				<span class="navbar-toggler-icon three"></span>
			</button>
			<div class="navbar-collapse" id="navbarNav">
				<span class="close-btn" onclick="closeMenu()">&times;</span>
				<ul class="navbar-nav">
					<li class="nav-item mt-5">
						<?php echo $_SESSION['levelName'] ?>
						<h5>
							<?php echo $_SESSION['name'] ?>
						</h5>
					</li>
					<?php if($_SESSION['level'] == '0'): ?>
					<li class="nav-item">
						<a class="nav-link px-3" style="cursor: pointer" data-bs-toggle="modal" data-bs-target="#createUserModal">
							<i class="fas fa-user-plus"></i> Nuevo usuario
						</a>
					</li>
					<!-- modal de accesos unauthorizedAccessModal -->
					<li class="nav-item">
						<a class="nav-link px-3" href="" data-bs-toggle="modal" data-bs-target="#folderPermissionsModal">
							<i class="fas fa-lock"></i> Accesos no autorizados
						</a>
					</li>
					<?php endif ?>
					<li class="nav-item">
						<a class="nav-link px-3" href="" onclick="logout()">
							<i class="fas fa-sign-out-alt"></i> Cerrar sesión
						</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
</header>
