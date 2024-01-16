// Inicializar Dropzone.js
Dropzone.autoDiscover = false;
let myDropzone = new Dropzone("#imagen_noticia", {
  url: "/ruby/design/Imagenes",
  paramName: "imagen",
  maxFilesize: 5,
  maxFiles: 1,
  addRemoveLinks: true,
  acceptedFiles: ".jpg, .jpeg, .png",
  dictRemoveFile: "Eliminar",
  init: function () {
    var dz = this;
    this.on("addedfile", function (file) {
      if (file.size > this.options.maxFilesize * 1024 * 1024) {
        Swal.fire(
          "Error",
          "La imagen excede el tamaño máximo permitido de 5MB",
          "error"
        );
        dz.removeFile(file); // Elimina el archivo del dropzone
      }
    });
    this.on("success", function (file, response) {
      console.log(response);
    });
  },
});

let myDropzone_edit = new Dropzone("#imagen_noticia_edit", {
  url: "/ruby/design/Imagenes",
  paramName: "imagen_edit",
  maxFilesize: 5,
  maxFiles: 1,
  addRemoveLinks: true,
  acceptedFiles: ".jpg, .jpeg, .png",
  dictRemoveFile: "Eliminar",
  init: function () {
    var dz = this;
    this.on("addedfile", function (file) {
      if (file.size > this.options.maxFilesize * 1024 * 1024) {
        Swal.fire(
          "Error",
          "La imagen excede el tamaño máximo permitido de 5MB",
          "error"
        );
        dz.removeFile(file); // Elimina el archivo del dropzone
      }
    });
    this.on("success", function (file, response) {
      console.log(response);
      // Resto del código para manejar la respuesta exitosa del servidor
    });
  },
});

$(document).ready(function () {
  let table = $("#TablaNoticias").DataTable({
    ajax: {
      url: "TablaNoticias.php",
      data: {
        opcion: "all",
      },
      dataSrc: "",
    },

    columns: [
      {
        data: "TITULO",
        className: "text-center",
      },
      {
        data: "IMAGEN",
        className: "text-center",
        render: function (data, type, row) {
          if (type === "display") {
            return (
              '<img src="' +
              data +
              '" alt="Miniatura" style="max-height: 50px; max-width: 50px;">'
            );
          }
          return data;
        },
      },
      {
        data: "FECHA",
        className: "text-center",
      },
      {
        data: "AUTOR",
        className: "text-center",
      },
      {
        data: "ID",
        className: "text-center",
        createdCell: function (td, cellData, rowData, rowIndex, colIndex) {
          $(td).css({
            display: "flex",
            "justify-content": "center",
            "align-items": "center",
          }); // Agregamos el estilo display: flex; a la celda td
          $(td).html(
            '<button type="button" value="' +
              rowData.ID +
              '" class="btn btn-outline-success editBtn bi bi-pencil" title="Editar" style="margin-right: 2px;" data-bs-toggle="modal" data-bs-target="#ModalEditar"></button>' +
              '<button type="button" value="' +
              rowData.ID +
              '" title="Eliminar" class="btn btn-outline-danger deleteBtn bi bi-trash m-0" style="margin-left: 2px;"></button>'
          ); // Agregamos los botones directamente al contenido de la celda utilizando la función html()
        },
      },
    ],

    responsive: true,
    autoWidth: false,

    order: [[0, "asc"]],
    pageLength: 10,
    lengthMenu: [
      [10, 20, 30, -1],
      [10, 20, 30, "Todos"],
    ],
    language: {
      processing: "Procesando...",
      lengthMenu: "Mostrar _MENU_ registros",
      zeroRecords: "No se encontraron resultados",
      emptyTable: "Ningún dato disponible en esta tabla",
      info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
      infoFiltered: "(filtrado de un total de MAX registros)",
      search: "Buscar:",
      infoThousands: ",",
      loadingRecords: "Cargando...",
      paginate: {
        first: "Primero",
        last: "Último",
        next: "Siguiente",
        previous: "Anterior",
      },
      aria: {
        sortAscending: ": Activar para ordenar la columna de manera ascendente",
        sortDescending:
          ": Activar para ordenar la columna de manera descendente",
      },
      buttons: {
        copy: "Copiar",
        colvis: "Visibilidad",
        collection: "Colección",
        colvisRestore: "Restaurar visibilidad",
        copyKeys:
          "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br /> <br /> Para cancelar, haga clic en este mensaje o presione escape.",
        copySuccess: {
          1: "Copiada 1 fila al portapapeles",
          _: "Copiadas %d fila al portapapeles",
        },
        copyTitle: "Copiar al portapapeles",
        csv: "CSV",
        excel: "Excel",
        pageLength: {
          "-1": "Mostrar todas las filas",
          1: "Mostrar 1 fila",
          _: "Mostrar %d filas",
        },
        pdf: "PDF",
        print: "Imprimir",
      },
    },
  });

  document.addEventListener("submit", function (e) {
    if (e.target.id === "crear") {
      e.preventDefault();

      var formData = new FormData(e.target);
      formData.append("crear_noticia", true);

      // Agregar archivo cargado por Dropzone al FormData
      var file = myDropzone.files[0];
      formData.append("imagen", file);

      var xhr = new XMLHttpRequest();
      xhr.open("POST", "CRUD_Noticias.php", true);
      xhr.onload = function () {
        if (xhr.status === 200) {
          var response = xhr.responseText;
          try {
            var res = JSON.parse(response);
            if (res.status == 422) {
              Swal.fire("Error", res.message, "error");
            } else if (res.status == 200) {
              Swal.fire("Éxito", res.message, "success").then(() => {
                var modal = document.getElementById("ModalCrear");
                modal.style.display = "none";
                document.body.classList.remove("modal-open");
                var modalBackdrop =
                  document.getElementsByClassName("modal-backdrop")[0];
                modalBackdrop.parentNode.removeChild(modalBackdrop);

                document.getElementById("crear").reset();
                table.ajax.reload();
              });
            } else if (res.status == 500) {
              Swal.fire("Error", res.message, "error");
            }
          } catch (error) {
            console.error(error);
            Swal.fire(
              "Error",
              "Ocurrió un error al procesar la respuesta",
              "error"
            );
          }
        } else {
          Swal.fire(
            "Error",
            "Ocurrió un error al procesar la solicitud",
            "error"
          );
        }
      };

      xhr.send(formData);
    }
  });

  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("deleteBtn")) {
      e.preventDefault();

      Swal.fire({
        title: "¿Estás seguro?",
        text: "Esta acción no se puede deshacer",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
      }).then(function (result) {
        if (result.isConfirmed) {
          let noticia_id = e.target.value;
          var xhr = new XMLHttpRequest();
          xhr.open("POST", "CRUD_Noticias.php", true);
          xhr.setRequestHeader(
            "Content-type",
            "application/x-www-form-urlencoded"
          );
          xhr.onload = function () {
            if (xhr.status === 200) {
              var response = JSON.parse(xhr.responseText);
              if (response.status == 500) {
                Swal.fire("Error", response.message, "error");
              } else {
                Swal.fire("Éxito", response.message, "success");
                table.ajax.reload();
              }
            } else {
              Swal.fire(
                "Error",
                "Ocurrió un error al procesar la solicitud",
                "error"
              );
            }
          };
          xhr.send("eliminar_noticia=true&noticia_id=" + noticia_id);
        }
      });
    }
  });

  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("editBtn")) {
      e.preventDefault();

      noticia_id = e.target.value;

      var xhr = new XMLHttpRequest();
      xhr.open("GET", "CRUD_Noticias.php?noticia_id=" + noticia_id, true);
      xhr.onload = function () {
        if (xhr.status === 200) {
          var response = xhr.responseText;
          var res = JSON.parse(response);
          if (res.status == 404) {
            Swal.fire("Error", res.message, "error");
          } else if (res.status == 200) {
            document.getElementById("titulo_edit").value = res.data[0].TITULO;
            document.getElementById("fecha_edit").value = res.data[0].FECHA;
            document.getElementById("autor_edit").value = res.data[0].AUTOR;
            // Establecer el contenido en el editor TinyMCE
            tinymce.get("contenido_edit").setContent(res.data[0].CONTENIDO);
            // Establecer la imagen en el Dropzone
            var dropzone = Dropzone.forElement("#imagen_noticia_edit");
            dropzone.removeAllFiles(); // Eliminar cualquier archivo existente
            var imageUrl = res.data[0].IMAGEN;
            var extension = res.data[0].IMAGEN.split(".").pop().toLowerCase();
            fetch(imageUrl)
              .then((response) => response.blob())
              .then((blob) => {
                var fileName = "imagen." + extension;
                var fileType = "image/" + extension;
                var file = new File([blob], fileName, {
                  type: fileType,
                });
                dropzone.addFile(file);
              })
              .catch((error) => {
                console.error("Error al obtener la imagen:", error);
              });
          }
        } else {
          Swal.fire(
            "Error",
            "Ocurrió un error al procesar la solicitud",
            "error"
          );
        }
      };
      xhr.send();
    }
  });
  document.addEventListener("submit", function (e) {
    if (e.target.id === "editar") {
      e.preventDefault();

      let formDataEditar = new FormData(e.target);
      formDataEditar.append("editar_noticia", true);
      formDataEditar.append("noticia_id", noticia_id);

      // Agregar archivo cargado por Dropzone al FormData
      let file_edit = myDropzone_edit.files[0];
      formDataEditar.append("imagen_edit", file_edit);

      var xhr = new XMLHttpRequest();
      xhr.open("POST", "CRUD_Noticias.php", true);
      xhr.onload = function () {
        if (xhr.status === 200) {
          var response = xhr.responseText;
          try {
            var res = JSON.parse(response);
            if (res.status == 422) {
              Swal.fire("Error", res.message, "error");
            } else if (res.status == 200) {
              Swal.fire("Éxito", res.message, "success").then(() => {
                let modal_edit = document.getElementById("ModalEditar");
                modal_edit.style.display = "none";
                document.body.classList.remove("modal-open");
                var modalBackdrop =
                  document.getElementsByClassName("modal-backdrop")[0];
                modalBackdrop.parentNode.removeChild(modalBackdrop);

                document.getElementById("editar").reset();
                table.ajax.reload();
              });
              table.ajax.reload();
            } else if (res.status == 500) {
              Swal.fire("Error", res.message, "error");
            }
          } catch (error) {
            console.error(error);
            // Manejar el caso en el que la respuesta no sea un JSON válido
            Swal.fire(
              "Error",
              "Ocurrió un error al procesar la respuesta",
              "error"
            );
          }
        } else {
          Swal.fire(
            "Error",
            "Ocurrió un error al procesar la solicitud",
            "error"
          );
        }
      };
      xhr.send(formDataEditar);
    }
  });

  tinymce.init({
    selector: "textarea",
    height: 500,
    menubar: false,
    plugins: "advlist autolink lists link image charmap preview anchor",
    toolbar:
      "undo redo | formatselect | " +
      "bold italic backcolor | alignleft aligncenter " +
      "alignright alignjustify | bullist numlist outdent indent | " +
      "removeformat | help",
  });
});
