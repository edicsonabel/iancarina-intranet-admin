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
								<div class="card-title">Noticias Registrados</div>
								<button type="button" class="btn btn-success float-end bi bi-person" data-bs-toggle="modal" data-bs-target="#ModalCrear">
									Crear Noticia
								</button>
							</div>
							<div class="card-body">
								<div class="table-responsive">

									<table id="TablaNoticias" class="table custom-table">
										<thead>
											<tr>
												<th>Titulo</th>
												<th>Imagen</th>
												<th>Fecha</th>
												<th>Autor</th>
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
							Modificar Noticia
						</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<form id="editar" enctype="multipart/form-data">
						<div class="modal-body">
							<div class="row gx-3">
								<div class="col-sm-4 col-12">
									<div class="mb-3 dropzone-container">
										<div class="dropzone needsclick dz-clickable" id="imagen_noticia_edit">
											<div class="dz-message needsclick">
												<button type="button" class="dz-button">
													Cargar imagen.
												</button>
											</div>
										</div>
										<input type="file" name="imagen_edit" id="imagen_edit" style="display: none;">
									</div>
								</div>
								<div class="col-sm-8 col-12">
									<!-- Form Field Start -->
									<div class="mb-3">
										<label for="fullName" class="form-label">Titulo</label>
										<input type="text" class="form-control" id="titulo_edit" name="titulo_edit" />
									</div>
									<div class="row gx-3">
										<div class="col-6">
											<!-- Form Field Start -->
											<div class="mb-3">
												<label class="form-label">Autor</label>
												<input type="tex" class="form-control" id="autor_edit" name="autor_edit" />
											</div>
										</div>
										<div class="col-6">
											<!-- Form Field Start -->
											<div class="mb-3">
												<label class="form-label">Fecha</label>
												<div class="input-group">
													<input type="text" class="form-control datepicker-opens-left" id="fecha_edit" name="fecha_edit" placeholder="DD/MM/YYYY" />
													<span class="input-group-text">
														<i class="bi bi-calendar4"></i>
													</span>
												</div>
											</div>
										</div>

									</div>
								</div>
							</div>
							<div class="col-12">
								<!-- Form Field Start -->
								<div class="mb-3">
									<label class="form-label">Contenido</label>
									<textarea class="form-control h-100" rows="2" id="contenido_edit" name="contenido_edit"></textarea>
								</div>
							</div>
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-outline-secondary">
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
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title h4" id="ModalCrear">
							Cargar Noticia
						</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<form id="crear" enctype="multipart/form-data">
						<div class="modal-body">
							<div class="row gx-3">
								<div class="col-sm-4 col-12">
									<div class="mb-3 dropzone-container">
										<div class="dropzone needsclick dz-clickable" id="imagen_noticia">
											<div class="dz-message needsclick">
												<button type="button" class="dz-button">
													Cargar imagen.
												</button>
											</div>
										</div>
										<input type="file" name="imagen" style="display: none;">
									</div>
								</div>
								<div class="col-sm-8 col-12">
									<!-- Form Field Start -->
									<div class="mb-3">
										<label for="fullName" class="form-label">Titulo</label>
										<input type="text" class="form-control" id="titulo" name="titulo" />
									</div>
									<div class="row gx-3">
										<div class="col-6">
											<!-- Form Field Start -->
											<div class="mb-3">
												<label class="form-label">Autor</label>
												<input type="tex" class="form-control" id="autor" name="autor" />
											</div>
										</div>
										<div class="col-6">
											<!-- Form Field Start -->
											<div class="mb-3">
												<label class="form-label">Fecha</label>
												<div class="input-group">
													<input type="text" class="form-control datepicker-opens-left" id="fecha" name="fecha" placeholder="DD/MM/YYYY" />
													<span class="input-group-text">
														<i class="bi bi-calendar4"></i>
													</span>
												</div>
											</div>
										</div>

									</div>
								</div>
							</div>
							<div class="col-12">
								<!-- Form Field Start -->
								<div class="mb-3">
									<label class="form-label">Contenido</label>
									<textarea class="form-control h-100" rows="2" id="contenido" name="contenido"></textarea>
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
	<script src="js/noticias.js"></script>