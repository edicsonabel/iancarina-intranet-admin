	<?php
	include_once('header.php');
	include_once('conexion.php');
	include_once('Verificar_Sesion.php');

	?>
	<div class="main-container">
		<?php include_once('menu.php'); ?>
		<div class="content-wrapper-scroll">
			<div class="main-header d-flex align-items-center justify-content-between position-relative">
				<div class="d-flex align-items-center justify-content-center">
					<div class="page-icon">
						<i class="bi bi-house"></i>
					</div>
					<div class="page-title d-none d-md-block">
						<h5>Bienvenido, <?php echo $_SESSION['Usuario']; ?></h5>
					</div>
				</div>
			</div>
			<div class="content-wrapper">
				<div class="row gx-3">
					<div class="col-sm-12 col-12">

						<div class="card">
							<div class="card-header">
								<div class="card-title">Usuarios Registrados</div>
								<button type="button" class="btn btn-success float-end bi bi-person" data-bs-toggle="modal" data-bs-target="#ModalCrear">
									Crear Usuario
								</button>
							</div>
							<div class="card-body">
								<div class="table-responsive">

									<table id="TablaUsuarios" class="table custom-table">
										<thead>
											<tr>
												<th>Usuario</th>
												<th>Nombre</th>
												<th>Departamento</th>
												<th>Nivel</th>
												<th>Opciones</th>
											</tr>
										</thead>
										<tbody>

										</tbody>

									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal Editar -->
		<div class="modal fade" id="ModalEditar" tabindex="-1" aria-labelledby="ModalEditar" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title h4" id="ModalEditar">
							Modificar Usuario
						</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<form id="editar">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-6">
									<label class="form-label">Nombre</label>
									<input type="text" class="form-control" id="nombre_edit" name="nombre_edit" required />

								</div>
								<div class="col-md-6">
									<label class="form-label">Departamento</label>
									<select class="form-select" id="depto_edit" name="depto_edit" required>
										<option selected disabled value="">Seleccione</option>
										<option>Legal</option>
										<option>Recursos Humanos</option>
										<option>Tecnologia</option>
									</select>

								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label class="form-label">Clave</label>
									<input type="password" class="form-control" id="clave_edit" name="clave_edit" required />

								</div>
								<div class="col-md-6">
									<label class="form-label">Nivel</label>
									<select class="form-select" id="nivel_edit" name="nivel_edit" required>
										<option selected disabled value="">Seleccione</option>
										<option value='1'>Administrador</option>
										<option value='2'>Usuario</option>
									</select>

								</div>
							</div>
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
								Cancelar
							</button>
							<button type="submit" id="btnModificar" class="btn btn-success">
								Modificar
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<!-- Modal Crear -->
		<div class="modal fade" id="ModalCrear" tabindex="-1" aria-labelledby="ModalCrear" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title h4" id="ModalCrear">
							Crear Usuario
						</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<form id="crear">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<label class="form-label">Usuario</label>
									<input type="text" class="form-control" id="usuario" name="usuario" required />
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label class="form-label">Nombre</label>
									<input type="text" class="form-control" id="nombre" name="nombre" required />

								</div>
								<div class="col-md-6">
									<label class="form-label">Departamento</label>
									<select class="form-select" id="depto" name="depto" required>
										<option selected disabled value="">Seleccione</option>
										<option value="Legal">Legal</option>
										<option value="Recursos Humanos">Recursos Humanos</option>
										<option value="Tecnologia">Tecnologia</option>
									</select>

								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label class="form-label">Clave</label>
									<input type="password" class="form-control" id="clave" name="clave" required />

								</div>
								<div class="col-md-6">
									<label class="form-label">Nivel</label>
									<select class="form-select" id="nivel" name="nivel" required>
										<option selected disabled value="">Seleccione</option>
										<option value="1">Administrador</option>
										<option value="2">Usuario</option>
									</select>

								</div>
							</div>
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
								Cancelar
							</button>
							<button type="submit" id="btnCrear" class="btn btn-success">
								Crear
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<!-- App Footer start -->
		<div class="app-footer">
			<span>Desarrollado por Keisha Sanchez & Edicson Gonzalez 2023</span>
		</div>
		<!-- App footer end -->
	</div>
	<?php
	include_once('footer.php');
	?>
	<script src="js/usuarios.js"></script>