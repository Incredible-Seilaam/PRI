<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:output method="html" indent="yes" />

  <xsl:template match="/hra">
    <html>
      <head>
        <title><xsl:value-of select="nazev" /></title>
        <link rel="stylesheet" href="css/style.css"/>
      </head>
      <body>
        <h1><xsl:value-of select="nazev" /></h1>
        <ul>
          <li><strong>Žánr:</strong> <xsl:value-of select="zanr" /></li>
          <li><strong>Platforma:</strong> <xsl:value-of select="platforma" /></li>
          <li><strong>Rok:</strong> <xsl:value-of select="rok" /></li>
          <li><strong>Průměrné hodnocení:</strong> <xsl:value-of select="prumer" /></li>
        </ul>
        <p><a href="index.php">⬅ Zpět na knihovnu</a></p>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
