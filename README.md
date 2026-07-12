# Desde la Línea — Portal Deportivo

Portal de noticias deportivas con scraper automático de RSS.

## Fuentes de scraping
- Infobae Deportes
- TyC Sports
- Ole
- ESPN Argentina
- Marca (Internacional)

## Instalación

```bash
docker-compose up -d
composer install
# Importar el esquema de base de datos
mysql -u root -proot desdelalinea < database/schema.sql
```

## Ejecutar el scraper

**Manual:**
```bash
php scrape.php
```

**Automático con cron (cada 30 min):**
```cron
*/30 * * * * php /var/www/html/scrape.php >> /var/log/scraper.log 2>&1
```



## Rutas
| Ruta | Descripción |
|------|-------------|
| `/` | Home con últimas noticias |
| `/noticias` | Listado paginado con filtro por categoría |
| `/noticia?id=X` | Detalle de noticia |
| `/login` | Login admin |
| `/admin` | Dashboard admin |
