# Entry Test – Laravel Location Management

Ứng dụng quản lý **Location** viết bằng Laravel (Blade) trên giao diện template **Limitless**, chạy hoàn toàn bằng Docker.

## Tính năng

- Đăng nhập, đăng xuất, quên mật khẩu qua email (Reset Password)
- Cập nhật hồ sơ cá nhân (tên, email, giới tính, avatar, đổi mật khẩu)
- CRUD Location, màn danh sách dùng DataTables server-side gọi API lấy dữ liệu (phân trang, tìm kiếm, sắp xếp)
- Seeder nạp 75 location từ file CSV
- Xoá mềm (soft delete) cho Location
- Tự động reset bảng Location về dữ liệu gốc mỗi ngày (Artisan command + Laravel Scheduler)

## Công nghệ

| Thành phần | Phiên bản |
|---|---|
| PHP (php-fpm) | 8.2 |
| Laravel | xem `app/composer.json` |
| MySQL | 8.0 |
| Nginx | stable-alpine |
| UI | Limitless (Bootstrap 5), jQuery DataTables |

## Yêu cầu hệ thống

- [Docker](https://docs.docker.com/get-docker/) và Docker Compose v2 (lệnh `docker compose`)
- Git (chỉ cần nếu lấy mã nguồn từ GitHub)
- Cổng **8080** (web) và **3307** (MySQL) trên máy chưa bị chiếm dụng

Không cần cài sẵn PHP, Composer hay MySQL trên máy.

## Cấu trúc thư mục

```
.
├── Dockerfile               # Image PHP-FPM 8.2 + extension + Composer
├── docker-compose.yml       # Dịch vụ: app (php-fpm), nginx, db (mysql), scheduler
├── docker/nginx/default.conf
└── app/                     # Mã nguồn Laravel (được mount vào /var/www)
    ├── app/Http/Controllers
    ├── app/Repositories     # BaseRepository, UserRepository, LocationRepository
    ├── database/seeders/data/locations.csv
    └── resources/views
```

## Hướng dẫn cài đặt

### Bước 1. Lấy mã nguồn

Giải nén file `mai_cong_tuan_entry_test.zip` rồi mở terminal tại thư mục vừa giải nén:

```bash
unzip mai_cong_tuan_entry_test.zip
cd mai_cong_tuan_entry_test
```

Thư mục này là thư mục gốc, chứa `docker-compose.yml`, `Dockerfile` và thư mục `app/`. Các lệnh bên dưới đều chạy tại đây.

> Thư mục `app/vendor` không có trong file zip, bạn sẽ tạo nó ở Bước 5 bằng `composer install`.

Nếu muốn lấy từ GitHub thay vì file zip:

```bash
git clone https://github.com/tuanmc0302/mai_cong_tuan_entry_test.git
cd mai_cong_tuan_entry_test
```

### Bước 2. Khai báo UID/GID của user hiện tại (Linux/macOS)

Container PHP chạy với user trùng UID/GID của máy bạn để file sinh ra (log, avatar upload…) không bị thuộc quyền `root`. Tạo file `.env` ở **thư mục gốc** (cùng cấp `docker-compose.yml`):

```bash
echo "LOCAL_UID=$(id -u)" > .env
echo "LOCAL_GID=$(id -g)" >> .env
```

Nếu bỏ qua bước này, giá trị mặc định là `1000`. Trên Windows có thể bỏ qua.

### Bước 3. Build và khởi động container

```bash
docker compose up -d --build
docker compose ps
```

Phải thấy 4 container `entry_test_app`, `entry_test_nginx`, `entry_test_mysql`, `entry_test_scheduler` ở trạng thái chạy (MySQL ở trạng thái `healthy`). Container `entry_test_scheduler` có thể tạm báo lỗi hoặc khởi động lại cho đến khi bạn hoàn tất Bước 5, đó là bình thường.

### Bước 4. Tạo file cấu hình Laravel

```bash
cp app/.env.example app/.env
```

Mở `app/.env` và kiểm tra các biến dưới đây (xem bảng ở mục [Cấu hình môi trường](#cấu-hình-môi-trường)). Với Docker của project, các giá trị bắt buộc là:

```env
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=entry_test_db
DB_USERNAME=entry_test_user
DB_PASSWORD=entry_test_password

SESSION_DRIVER=file
MAIL_MAILER=log

ADMIN_EMAIL=tuanmc0302@gmail.com
ADMIN_PASSWORD=password123
```

> `DB_HOST` phải là `db` (tên service trong docker-compose), **không phải** `127.0.0.1`. Cổng `3307` chỉ dùng khi kết nối từ máy host (DBeaver, MySQL Workbench…).

### Bước 5. Cài thư viện, tạo key, tạo bảng và dữ liệu mẫu

```bash
docker compose exec -u www-data -e COMPOSER_HOME=/tmp/composer app composer install
docker compose exec -u www-data app php artisan key:generate
docker compose exec -u www-data app php artisan migrate --seed
docker compose exec -u www-data app php artisan storage:link
```

| Lệnh | Mục đích |
|---|---|
| `composer install` | Cài thư viện PHP vào `app/vendor` |
| `key:generate` | Sinh `APP_KEY` trong `app/.env` |
| `migrate --seed` | Tạo bảng và nạp dữ liệu (tài khoản mặc định + 75 location từ CSV) |
| `storage:link` | Tạo symlink `public/storage` để hiển thị ảnh avatar đã upload |

Sau khi hoàn tất, khởi động lại scheduler để nó nhận cấu hình mới:

```bash
docker compose restart scheduler
```

### Bước 6. Truy cập ứng dụng

Mở http://localhost:8080 và đăng nhập bằng tài khoản mặc định:

| Email | Mật khẩu |
|---|---|
| `tuanmc0302@gmail.com` | `password123` |

Tài khoản này được tạo trong `app/database/seeders/DatabaseSeeder.php`. Hãy đổi mật khẩu tại trang Profile sau khi đăng nhập.

## Cấu hình môi trường

Các biến trong `app/.env`:

| Biến | Giá trị gợi ý | Ý nghĩa |
|---|---|---|
| `APP_URL` | `http://localhost:8080` | URL gốc của ứng dụng (khớp với cổng nginx) |
| `APP_DEBUG` | `true` khi dev, `false` khi triển khai thật | Hiển thị chi tiết lỗi |
| `DB_HOST` | `db` | Tên service MySQL trong Docker |
| `DB_PORT` | `3306` | Cổng MySQL **bên trong** mạng Docker |
| `DB_DATABASE` | `entry_test_db` | Khớp `MYSQL_DATABASE` trong `docker-compose.yml` |
| `DB_USERNAME` | `entry_test_user` | Khớp `MYSQL_USER` |
| `DB_PASSWORD` | `entry_test_password` | Khớp `MYSQL_PASSWORD` |
| `SESSION_DRIVER` | `file` | Lưu session bằng file, không cần thêm bảng `sessions` |
| `MAIL_*` | xem bên dưới | Gửi email đặt lại mật khẩu |

Nếu đổi thông tin DB trong `docker-compose.yml`, phải đổi tương ứng trong `app/.env` và xoá volume cũ (`docker compose down -v`) để MySQL khởi tạo lại.

Sau khi sửa `app/.env`, chạy lại: `docker compose exec -u www-data app php artisan config:clear`.

### Cấu hình gửi email (Quên mật khẩu)

**Cách 1 – Ghi email vào log (mặc định, không cần tài khoản mail):**

```env
MAIL_MAILER=log
```

Sau khi gửi form "Forgot password", mở file `app/storage/logs/laravel.log` và tìm đoạn chứa `reset-password/` để lấy link đặt lại mật khẩu. Lưu ý nội dung email trong log được mã hoá quoted-printable: ký tự `=` hiển thị thành `=3D`, và dòng dài bị ngắt bằng dấu `=` ở cuối dòng. Khi copy link, hãy ghép các dòng lại và đổi `=3D` về `=`.

**Cách 2 – Gửi mail thật qua Gmail SMTP:**

Bật xác thực 2 bước cho tài khoản Google, tạo **App Password** (16 ký tự) rồi cấu hình:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_account@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_account@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

Có thể dùng dịch vụ test mail như Mailtrap bằng cách điền thông tin SMTP họ cung cấp vào các biến trên. Link đặt lại mật khẩu có hiệu lực 60 phút, và mỗi email chỉ được yêu cầu lại sau 60 giây.

## Danh sách route

| Method | URL | Tên route | Controller@method | Yêu cầu đăng nhập |
|---|---|---|---|---|
| GET | `/login` | `login` | `LoginController@showLoginForm` | Không (guest) |
| POST | `/login` | – | `LoginController@login` | Không (guest) |
| GET | `/forgot-password` | `password.request` | `ForgotPasswordController@showForm` | Không (guest) |
| POST | `/forgot-password` | `password.email` | `ForgotPasswordController@sendLink` | Không (guest) |
| GET | `/reset-password/{token}` | `password.reset` | `ResetPasswordController@showForm` | Không (guest) |
| POST | `/reset-password` | `password.update` | `ResetPasswordController@reset` | Không (guest) |
| POST | `/logout` | `logout` | `LoginController@logout` | Có |
| GET | `/profile/edit` | `profile.edit` | `ProfileController@edit` | Có |
| PUT | `/profile` | `profile.update` | `ProfileController@update` | Có |
| GET | `/locations` | `locations.index` | `LocationController@index` | Có |
| GET | `/locations/create` | `locations.create` | `LocationController@create` | Có |
| POST | `/locations` | `locations.store` | `LocationController@store` | Có |
| GET | `/locations/{location}/edit` | `locations.edit` | `LocationController@edit` | Có |
| PUT | `/locations/{location}` | `locations.update` | `LocationController@update` | Có |
| DELETE | `/locations/{location}` | `locations.destroy` | `LocationController@destroy` | Có |
| GET | `/api/locations` | `api.locations.index` | `LocationController@datatable` | Có (session) |

`/api/locations` là API nguồn dữ liệu cho DataTables server-side. Tham số hỗ trợ: `draw`, `start`, `length`, `search[value]`, `order[0][column]`, `order[0][dir]`.

Xem danh sách đầy đủ bằng lệnh:

```bash
docker compose exec -u www-data app php artisan route:list
```

## Dữ liệu mẫu (Seeder)

- File nguồn: `app/database/seeders/data/locations.csv` (cột `ID, Name, Lat, Lng, Status`)
- Seeder: `LocationSeeder` xoá sạch bảng `locations` rồi nạp lại toàn bộ, nên chạy nhiều lần vẫn cho kết quả giống nhau.

```bash
# Nạp lại chỉ dữ liệu location
docker compose exec -u www-data app php artisan db:seed --class=LocationSeeder

# Làm lại toàn bộ database
docker compose exec -u www-data app php artisan migrate:fresh --seed
```

## Tự động reset dữ liệu hàng ngày

Bảng `locations` được đưa về dữ liệu gốc (75 location trong `app/database/seeders/data/locations.csv`) **mỗi ngày lúc 00:00 giờ Việt Nam (`Asia/Ho_Chi_Minh`)**. Mọi thay đổi do người dùng thêm, sửa, xoá (kể cả các bản ghi đã xoá mềm) đều bị xoá. Bảng `users` không bị ảnh hưởng.

| Thành phần | Vị trí |
|---|---|
| Artisan command | `locations:reset` (`app/Console/Commands/ResetLocations.php`) |
| Lịch chạy | `routes/console.php` (Laravel 11+) hoặc `app/Console/Kernel.php` (Laravel 10) |
| Tiến trình chạy lịch | Service `scheduler` trong `docker-compose.yml` (`php artisan schedule:work`) |

Service `scheduler` khởi động cùng các container khác khi chạy `docker compose up -d`, không cần cấu hình cron. Nếu bạn khởi động project lần đầu và thấy container này báo lỗi hoặc tự khởi động lại, đó là do `composer install` hoặc `app/.env` chưa sẵn sàng. Sau khi hoàn tất Bước 5, chạy `docker compose restart scheduler`.

**Kiểm tra lịch đã đăng ký:**

```bash
docker compose exec -u www-data app php artisan schedule:list
```

**Chạy reset thủ công ngay lập tức:**

```bash
docker compose exec -u www-data app php artisan locations:reset
```

**Kiểm tra scheduler đang chạy:**

```bash
docker compose ps scheduler
docker compose logs --tail 20 scheduler
```

Kết quả mỗi lần reset được ghi vào `app/storage/logs/laravel.log` (dòng `locations:reset hoàn tất.`). Để thử nhanh không cần đợi đến nửa đêm, đổi tạm lịch thành `->everyMinute()`, đợi 1 đến 2 phút rồi xem log, sau đó nhớ đổi lại `->dailyAt('00:00')`.

Để đổi giờ chạy, sửa `dailyAt('00:00')` trong file lịch ở bảng trên, ví dụ `dailyAt('03:30')`.

## Lệnh thường dùng

```bash
docker compose up -d                 # Khởi động
docker compose down                  # Dừng và xoá container (giữ dữ liệu DB)
docker compose down -v               # Dừng và xoá cả dữ liệu DB
docker compose logs -f app           # Xem log PHP
docker compose logs -f scheduler     # Xem log scheduler (reset hằng ngày)
docker compose exec -u www-data app bash    # Vào shell container
tail -f app/storage/logs/laravel.log        # Xem log Laravel
```

## Xử lý sự cố

| Triệu chứng | Nguyên nhân / Cách xử lý |
|---|---|
| `port is already allocated` khi `up` | Cổng 8080 hoặc 3307 đang bị chiếm. Đổi phần `ports` trong `docker-compose.yml` (ví dụ `"8081:80"`) và cập nhật `APP_URL`. |
| `SQLSTATE[HY000] [2002] Connection refused` | `DB_HOST` đang không phải `db`, hoặc MySQL chưa sẵn sàng. Kiểm tra `docker compose ps`, đợi trạng thái `healthy`, rồi `config:clear`. |
| `Access denied for user` | Thông tin DB trong `app/.env` không khớp `docker-compose.yml`. Nếu vừa đổi mật khẩu DB, chạy `docker compose down -v` rồi `up` lại. |
| `No application encryption key has been specified` | Chưa chạy `php artisan key:generate`. |
| `Permission denied` khi ghi `storage` hoặc `bootstrap/cache` | UID/GID container khác user máy host. Làm lại Bước 2 rồi `docker compose up -d --build`. Có thể chạy thêm `chmod -R 775 app/storage app/bootstrap/cache`. |
| Ảnh avatar không hiển thị (404) | Chưa tạo symlink. Chạy `php artisan storage:link`. Nếu đã tồn tại link hỏng, xoá `app/public/storage` rồi chạy lại. |
| `419 Page Expired` | Session/CSRF hết hạn. Tải lại trang rồi gửi lại form. |
| Không thấy email đặt lại mật khẩu | Nếu `MAIL_MAILER=log`, link nằm trong `app/storage/logs/laravel.log`. Nếu dùng SMTP, kiểm tra thư mục spam và App Password. |
| Sửa `.env` nhưng không có tác dụng | Chạy `php artisan config:clear`. |

## Ghi chú

- Mã nguồn tổ chức theo **Repository pattern**: `Controller` → `Repository` → `BaseRepository` → `Model`. Muốn thêm repository mới, kế thừa `BaseRepository` và khai báo `model()` trả về class Model.
- Thư mục `app/public/assets/demo/maps` của template Limitless không có trong repo vì chứa token của bên thứ ba (GitHub push protection chặn). Ứng dụng không dùng bản đồ nên không bị ảnh hưởng.
- File `.env` (cả ở thư mục gốc lẫn `app/`) không được commit, mỗi máy tự tạo theo hướng dẫn ở trên.
