# Desde la Línea — Portal Deportivo

Portal de noticias deportivas con scraper automático de RSS, enfocado en el **Mundial 2026** y el **fútbol argentino local**.

## Fuentes de scraping
Todas son medios argentinos (nacionales y de provincia). Cada noticia se clasifica sola en una de 2 categorías:
- **Mundial 2026**: si el título/copete menciona el Mundial / Copa del Mundo.
- **Argentina**: el resto de las noticias de fútbol local (clubes, AFA, Selección, torneos).

Medios incluidos: Olé, TyC Sports, Infobae Deportes, Clarín Deportes, La Nación Deportes, Página/12 Deportes, Ámbito Deportes, Perfil Deportes, Diario Popular, ESPN Argentina, La Voz del Interior (Córdoba), La Capital (Rosario), Los Andes (Mendoza), La Gaceta (Tucumán), Río Negro y El Litoral (Santa Fe).

> Los diarios de provincia cambian de tanto en tanto la URL de su RSS. Si una fuente deja de traer noticias, fijate el log de `/admin` → "Ejecutar Scraper Ahora" (marca cada fuente con ✅ o ❌) y actualizá esa URL puntual en `app/Services/Scraper.php`. El resto de las fuentes sigue funcionando igual aunque una falle.

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

**Desde el panel admin:**  
Entrá a `/admin` → botón "Ejecutar Scraper Ahora".

## Credenciales por defecto
- Email: `admin@desdelalinea.com`  
- Password: `password`

## Rutas
| Ruta | Descripción |
|------|-------------|
| `/` | Home con últimas noticias |
| `/noticias` | Listado paginado con filtro por categoría |
| `/noticia?id=X` | Detalle de noticia |
| `/login` | Login admin |
| `/admin` | Dashboard admin |
