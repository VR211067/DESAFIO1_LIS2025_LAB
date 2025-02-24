document.addEventListener("DOMContentLoaded", function () {
    console.log("Script cargado"); // script cargado correctamente 

    // FILTRADO POR NOMBRE (BUSCADOR)
    let buscador = document.getElementById("buscador");
    if (buscador) {
        buscador.addEventListener("input", function () {
            const texto = this.value.toLowerCase(); //obtiene el texto ingresado en el buscador y lo convierte a minúsculas
            document.querySelectorAll(".producto").forEach(producto => {
                const nombre = producto.querySelector(".card-title").textContent.toLowerCase(); // Obtiene el nombre del producto
                producto.style.display = nombre.includes(texto) ? "block" : "none"; // Muestra u oculta el producto según la búsqueda
            });
        });
    } else {
        console.warn("El elemento #buscador no existe en esta página.");
    }

    // Modal de detalles del producto
    document.querySelectorAll(".ver-detalle").forEach(boton => {
        boton.addEventListener("click", function () {
            // datos del producto desde los atributos del botón y se asignan al modal
            document.getElementById("modalTitulo").textContent = this.dataset.nombre;
            document.getElementById("modalDescripcion").textContent = this.dataset.descripcion;
            document.getElementById("modalImagen").src = this.dataset.imagen;
            document.getElementById("modalPrecio").textContent = this.dataset.precio;
            
            // Se determina la disponibilidad del producto y se asigna la clase correspondiente
            let disponibilidad = this.dataset.existencias > 0 ? "Disponible" : "Producto No Disponible";
            document.getElementById("modalDisponibilidad").textContent = disponibilidad;
            document.getElementById("modalDisponibilidad").className = this.dataset.existencias > 0 ? "text-success" : "text-danger";
        });
    });

    // Efecto de hover en productos 
    document.querySelectorAll(".card").forEach(card => {
        card.addEventListener("mouseover", () => card.style.boxShadow = "rgb(255, 115, 0) 0px 0px 15px");
        card.addEventListener("mouseout", () => card.style.boxShadow = "none");
    });

    // Animación las tarjetas de productos
    document.querySelectorAll(".producto").forEach((producto, index) => {
        setTimeout(() => {
            producto.style.opacity = "1";
            producto.style.transform = "translateY(0)";
        }, index * 150); 
    });

    // FILTRADO POR CATEGORÍA DESDE EL NAVBAR
    document.querySelectorAll(".dropdown-item").forEach(enlace => {
        enlace.addEventListener("click", function (event) {
            event.preventDefault(); // Previene la recarga de la página al hacer clic en el enlace
            const categoriaSeleccionada = this.getAttribute("href").split("=")[1]; // categoria seleccionada desde enlace

            document.querySelectorAll(".producto").forEach(producto => {
                const categoriaProducto = producto.getAttribute("data-categoria"); // Obtiene la categoría del producto
                producto.style.display = (categoriaSeleccionada === "all" || categoriaProducto === categoriaSeleccionada) ? "block" : "none";
            });
        });
    });

    //  MODAL DE EDICIÓN (ADMIN)
    document.querySelectorAll(".editar").forEach(button => {
        button.addEventListener("click", function () {
           // console.log("Datos del botón al hacer clic:", this.dataset); // Depuración

           // console.log("Datos cargados:", this.dataset);
            //datos del producto en modal edicion
            document.getElementById("editCodigo").value = this.dataset.codigo;
            document.getElementById("editNombre").value = this.dataset.nombre;
            document.getElementById("editDescripcion").value = this.dataset.descripcion;
            document.getElementById("editCategoria").value = this.dataset.categoria;
            document.getElementById("editPrecio").value = this.dataset.precio;
            document.getElementById("editExistencias").value = this.dataset.existencias;
            document.getElementById("editImagenPreview").src = this.dataset.imagen;
        });
    });
    
});
