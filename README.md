# Generador de Tarjetas Dinámicas con JavaScript

Este proyecto permite generar tarjetas HTML de manera dinámica haciendo clic en un botón. Cada tarjeta contiene un título, subtítulo, imagen, y botones para editar o eliminar su contenido.

## 📋 Funcionalidades

- Crear nuevas tarjetas con contenido predefinido.
- Editar título, subtítulo y URL de imagen de cada tarjeta.
- Eliminar cualquier tarjeta del contenedor.
- Interfaz interactiva sin necesidad de recargar la página.

## 🧩 Estructura de una Tarjeta

Cada tarjeta incluye:

- Un **título (`<h2>`)**: "Un Titulo".
- Un **subtítulo (`<h4>`)**: "Un Subtitulo".
- Una **imagen (`<img>`)** con una URL predeterminada.
- Un **input de texto (`<input type="text">`)** oculto para modificar la URL de la imagen.
- Un **botón "Eliminar"** que borra la tarjeta.
- Un **botón "Editar"/"Aceptar"** que permite editar el contenido textual y la imagen.
