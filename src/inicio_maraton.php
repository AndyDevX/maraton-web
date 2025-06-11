<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Formulario Maratón</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f3f3f3;
      padding: 30px;
    }
    .form-container {
      max-width: 600px;
      margin: auto;
      background-color: white;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }
    label {
      display: block;
      margin: 12px 0 5px;
      font-weight: bold;
    }
    select, input[type="submit"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
    }
    input[type="submit"] {
      background-color: #007bff;
      color: white;
      font-weight: bold;
      cursor: pointer;
      margin-top: 20px;
    }
    input[type="submit"]:hover {
      background-color: #0056b3;
    }
    .hidden {
      display: none;
    }
  </style>
  <script>
    function toggleArticulos() {
      const tema = document.getElementById('tema').value;
      const articulos = document.getElementById('articulos-container');
      articulos.style.display = (tema === 'cff_2') ? 'block' : 'none';
    }
  </script>
</head>
<body>

<div class="form-container">
  <h2>Configuración de Preguntas</h2>
  <form method="POST" action="maraton.php">
    <label for="tema">Tema:</label>
    <select name="tema" id="tema" onchange="toggleArticulos()" required>
      <option value="">-- Selecciona un tema --</option>
      <option value="Impuesto Sobre la Renta">Impuesto Sobre la Renta</option>
      <option value="cff_2">Código Fiscal de la Federación</option>
      <option value="Impuesto al Valor Agregado">Impuesto al Valor Agregado</option>
      <option value="Impuesto Especial Sobre Producción y Servicios">Impuesto Especial Sobre Producción y Servicios</option>
    </select>

    <div id="articulos-container" class="hidden">
      <label for="articulo">Artículo:</label>
      <select name="articulo" id="articulo">
        <option value="">-- Selecciona un artículo --</option>
        <option value="79">79</option><option value="81">81</option>
        <option value="82 A">82 A</option><option value="82 C">82 C</option>
        <option value="82 E">82 E</option><option value="82 G">82 G</option>
        <option value="83">83</option><option value="84 A">84 A</option>
        <option value="84 C">84 C</option><option value="84 E">84 E</option>
        <option value="84 G">84 G</option><option value="84 I">84 I</option>
        <option value="84 K">84 K</option><option value="84 M">84 M</option>
        <option value="85">85</option><option value="86 A">86 A</option>
        <option value="86 C">86 C</option><option value="86 E">86 E</option>
        <option value="86 G">86 G</option><option value="86 I">86 I</option>
        <option value="87">87</option><option value="89">89</option>
        <option value="90 A">90 A</option><option value="91">91</option>
        <option value="91 A">91 A</option><option value="102">102</option>
        <option value="108">108</option>
        <option value="79, 82 A,82 C,82 E,82 G">79,82 A al 82 G </option>
        <option value="83, 84 A,84 C,84 E,84 G,84 I,84 K,84 M">83,84 A al 84 M </option>
        <option value="85, 86 A,86 C,86 E,86 G,86 I,87,89,90 A, 91, 91 A, 102, 108">85 A al 108 </option>
        <!--  <option value="108">108</option><option value="Todos">Todos</option>-->
      </select>
    </div>

    <label for="tipo_pregunta">Tipo de pregunta:</label>
    <select name="tipo_pregunta" id="tipo_pregunta" required>
      <option value="">-- Selecciona un tipo --</option>
      <option value="teoria">Teoría</option>
      <option value="practica">Práctica</option>
    </select>

    <label for="cantidad">Cantidad de preguntas:</label>
    <select name="cantidad" id="cantidad" required>
      <option value="">-- Selecciona cantidad --</option>
      <option value="1">1 </option>
      <option value="5">5</option><option value="10">10</option>
      <option value="15">15</option><option value="20">20</option>
      <option value="25">25</option><option value="30">30</option>
      <option value="todas">Todas</option>
    </select>

    <input type="submit" value="Enviar">
  </form>
</div>

</body>
</html>
