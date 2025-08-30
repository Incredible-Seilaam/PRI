<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:template match="/hra">
    <html>
      <body>
        <h2><xsl:value-of select="nazev"/></h2>
        <p><b>Žánr:</b> <xsl:value-of select="zanr"/></p>
        <p><b>Platforma:</b> <xsl:value-of select="platforma"/></p>
        <p><b>Rok:</b> <xsl:value-of select="rok"/></p>
        <p><b>Hodnocení:</b> <xsl:value-of select="hodnoceni"/></p>
        <p><a href="index.php">Zpět</a></p>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
