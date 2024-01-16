$(document).ready(function () {
  let table = $("#TablaDocumentos").DataTable({
    ajax: {
      url: "TablaDocumentos.php",
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
        data: "DESCRIPCION",
        className: "text-center",
      },
      {
        data: "DEPARTAMENTO",
        className: "text-center",
      },
      {
        data: "UBICACION",
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
              '" class="btn btn-outline-success editBtn bi bi-pencil" title="Editar"  style="margin-right: 2px;" data-bs-toggle="modal" data-bs-target="#ModalEditar" ></button>' +
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

  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("editBtn")) {
      e.preventDefault();

      documento_id = e.target.value;

      var xhr = new XMLHttpRequest();
      xhr.open("GET", "CRUD_Documentos.php?documento_id=" + documento_id, true);
      xhr.onload = function () {
        if (xhr.status === 200) {
          var response = xhr.responseText;
          var res = JSON.parse(response);
          if (res.status == 404) {
            Swal.fire("Error", res.message, "error");
          } else if (res.status == 200) {
            document.getElementById("titulo_edit").value = res.data[0].TITULO;
            document.getElementById("descripcion_edit").value =
              res.data[0].DESCRIPCION;

            document.getElementById("ModalEditar").style.display = "block";
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
          let documento_id = e.target.value;
          var xhr = new XMLHttpRequest();
          xhr.open("POST", "CRUD_Documentos.php", true);
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
          xhr.send("eliminar_documento=true&documento_id=" + documento_id);
        }
      });
    }
  });

  document.addEventListener("submit", function (e) {
    if (e.target.id === "crear") {
      e.preventDefault();

      let fileInput = document.getElementById("documento");
      let file = fileInput.files[0];

      // Validar la extensión del archivo
      let allowedExtensions = [".pdf", ".doc", ".docx"];
      let fileExtension = file.name
        .substring(file.name.lastIndexOf("."))
        .toLowerCase();
      if (!allowedExtensions.includes(fileExtension)) {
        Swal.fire("Error", "La extensión del archivo no es válida", "error");
        return;
      }
      // Validar el tamaño del archivo (en bytes)
      let maxSizeInBytes = 5242880; // 5 MB
      if (file.size > maxSizeInBytes) {
        Swal.fire(
          "Error",
          "El tamaño del archivo supera el límite permitido",
          "error"
        );
        return;
      }

      let formDataCrear = new FormData(e.target);
      formDataCrear.append("crear_documento", true);

      var xhr = new XMLHttpRequest();
      xhr.open("POST", "CRUD_Documentos.php", true);
      xhr.onload = function () {
        if (xhr.status === 200) {
          var response = xhr.responseText;
          console.log(response);
          try {
            var res = JSON.parse(response);
            if (res.status == 422) {
              Swal.fire("Error", res.message, "error");
            } else if (res.status == 200) {
              Swal.fire("Éxito", res.message, "success").then(() => {
                let modal = document.getElementById("ModalCrear");
                modal.style.display = "none";
                document.body.classList.remove("modal-open");
                let modalBackdrop =
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

      xhr.send(formDataCrear);
    }
  });

  document.addEventListener("submit", function (e) {
    if (e.target.id === "editar") {
      e.preventDefault();

      let formDataEditar = new FormData(e.target);
      formDataEditar.append("editar_documento", true);
      formDataEditar.append("documento_id", documento_id);

      var xhr = new XMLHttpRequest();
      xhr.open("POST", "CRUD_Documentos.php", true);
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
});
