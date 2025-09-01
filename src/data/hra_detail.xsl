<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:output method="html" indent="yes"/>

  <xsl:template match="/hra">
    <html>
      <head>
        <title><xsl:value-of select="nazev"/></title>
        <link rel="stylesheet" href="css/style.css"/>
      </head>
      <body class="full-center">
        <h1><xsl:value-of select="nazev"/></h1>

        <div class="game-detail">
          <p><span class="label">Žánr: </span> <xsl:value-of select="zanr"/></p>
          <p><span class="label">Platforma: </span> <xsl:value-of select="platforma"/></p>
          <p><span class="label">Rok: </span> <xsl:value-of select="rok"/></p>
          <p><span class="label">Průměrné hodnocení: </span> <xsl:value-of select="prumer"/></p>
        </div>

        <p><a href="index.php">⬅ Zpět na knihovnu</a></p>
      </body>
    </html>
  </xsl:template>

</xsl:stylesheet>
