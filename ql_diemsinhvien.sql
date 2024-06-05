CREATE TABLE Khoa (
    MaKhoa INT AUTO_INCREMENT PRIMARY KEY,
    TenKhoa NVARCHAR(100)
);

CREATE TABLE GiaoVien (
	MaGiaoVien varchar(15) PRIMARY KEY,
	TenGiaoVien NVARCHAR(100),
	DiaChi NVARCHAR(200),
	DienThoai NVARCHAR(200),
	MaKhoa INT,
	FOREIGN KEY (MaKhoa) REFERENCES Khoa(MaKhoa)
);

CREATE TABLE MonHoc (
    MaMH INT AUTO_INCREMENT PRIMARY KEY,
    TenMH NVARCHAR(100),
    SoTinChi INT,
    LoaiMonHoc NVARCHAR(100), 
	TrangThai NVARCHAR(50)
);

CREATE TABLE Lop (
    MaLop INT AUTO_INCREMENT PRIMARY KEY,
    TenLop NVARCHAR(100),
    MaKhoa INT,
    MaGiaoVien varchar(15),
    TrangThai NVARCHAR(20),
	MaMon INT,
    FOREIGN KEY (MaKhoa) REFERENCES Khoa(MaKhoa),
	FOREIGN KEY (MaGiaoVien) REFERENCES GiaoVien(MaGiaoVien),
    FOREIGN KEY (MaMon) REFERENCES MonHoc(MaMH)
);


CREATE TABLE SinhVien (
    MaSV VARCHAR(15) PRIMARY KEY,
    TenSV NVARCHAR(100),
    GioiTinh NVARCHAR(10),
    NgaySinh DATE,
    QueQuan NVARCHAR(100),
	TrangThai NVARCHAR(30)
);

CREATE TABLE SinhVien_Lop (
    MaSV varchar(15),
    MaLop INT,
    PRIMARY KEY (MaSV, MaLop),
    FOREIGN KEY (MaSV) REFERENCES SinhVien(MaSV),
    FOREIGN KEY (MaLop) REFERENCES Lop(MaLop)
);

CREATE TABLE Diem (
    MaSV varchar(15),
    MaMH INT,
    HocKy INT,
    DiemTieuLuan FLOAT,
	PhanTramDiemTieuLuan FLOAT,
    DiemThi FLOAT,
	PhanTramDiemThi FLOAT,
	DiemTB FLOAT,
    PRIMARY KEY (MaSV, MaMH, HocKy),
    FOREIGN KEY (MaSV) REFERENCES SinhVien(MaSV),
    FOREIGN KEY (MaMH) REFERENCES MonHoc(MaMH)
);

CREATE TABLE Admin (
	MaAdmin varchar(15) PRIMARY KEY,
	TenAdmin NVARCHAR(100),
	DiaChi NVARCHAR(200),
	DienThoai NVARCHAR(200)
);


CREATE TABLE UsersSinhVien (
    username VARCHAR(15) primary key NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role NVARCHAR(50) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
	foreign key (username) references sinhvien(masv)
);

CREATE TABLE UsersGiaoVien (
    username VARCHAR(15) primary key NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role NVARCHAR(50) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
	foreign key (username) references GiaoVien(MaGiaoVien)
);

CREATE TABLE UsersAdmin (
    username VARCHAR(15) primary key NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role NVARCHAR(50) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
	foreign key (username) references Admin(MaAdmin)
);


DELIMITER //

CREATE TRIGGER trg_BeforeInsertDiem
BEFORE INSERT ON Diem
FOR EACH ROW
BEGIN
    SET NEW.DiemTB = 
        IFNULL(NEW.DiemTieuLuan, 0) * IFNULL(NEW.PhanTramDiemTieuLuan, 0) / 100 +
        IFNULL(NEW.DiemThi, 0) * IFNULL(NEW.PhanTramDiemThi, 0) / 100;
END //

CREATE TRIGGER trg_BeforeUpdateDiem
BEFORE UPDATE ON Diem
FOR EACH ROW
BEGIN
    SET NEW.DiemTB = 
        IFNULL(NEW.DiemTieuLuan, 0) * IFNULL(NEW.PhanTramDiemTieuLuan, 0) / 100 +
        IFNULL(NEW.DiemThi, 0) * IFNULL(NEW.PhanTramDiemThi, 0) / 100;
END //

DELIMITER ;


INSERT INTO Khoa (TenKhoa) 
VALUES 
('Khoa Công nghệ thông tin'),
('Khoa Kinh tế'),
('Khoa Ngoại ngữ'),
('Khoa Khoa học xã hội và nhân văn'),
('Khoa Khoa học tự nhiên');

INSERT INTO GiaoVien (MaGiaoVien, TenGiaoVien, DiaChi, DienThoai, MaKhoa) 
VALUES 
('GV200', 'Nguyễn Văn A', 'Đại học ABC', '0123456789', 1),
('GV201', 'Trần Thị B', 'Đại học XYZ', '0987654321', 2),
('GV202', 'Phạm Văn C', 'Đại học MNO', '0123456789', 3),
('GV203', 'Lê Thị D', 'Đại học QRS', '0987654321', 4),
('GV204', 'Hoàng Văn E', 'Đại học UVW', '0123456789', 5),
('GV205', 'Vũ Thị F', 'Đại học GHI', '0987654321', 1),
('GV206', 'Đặng Văn G', 'Đại học JKL', '0123456789', 2),
('GV207', 'Bùi Thị H', 'Đại học TUV', '0987654321', 3),
('GV208', 'Trần Văn I', 'Đại học XYZ', '0123456789', 4),
('GV209', 'Nguyễn Thị K', 'Đại học MNO', '0987654321', 5);

INSERT INTO MonHoc (TenMH, SoTinChi, LoaiMonHoc, TrangThai) 
VALUES 
('Lập trình C', 3, 'Bắt buộc', 'Hoạt động'),
('Kinh tế học', 3, 'Bắt buộc', 'Hoạt động'),
('Tiếng Anh giao tiếp', 2, 'Bắt buộc', 'Hoạt động'),
('Văn học Việt Nam', 2, 'Bắt buộc', 'Hoạt động'),
('Giải tích', 4, 'Bắt buộc', 'Hoạt động'),
('Lập trình Python', 3, 'Bắt buộc', 'Hoạt động'),
('Quản trị kinh doanh', 3, 'Bắt buộc', 'Hoạt động'),
('Tiếng Nhật căn bản', 2, 'Bắt buộc', 'Hoạt động'),
('Triết học', 2, 'Bắt buộc', 'Hoạt động'),
('Toán cao cấp', 4, 'Bắt buộc', 'Hoạt động');

INSERT INTO Lop (TenLop, MaKhoa, MaGiaoVien, TrangThai, MaMon) 
VALUES 
('Lớp CNTT01', 1, 'GV200', 'Hoạt động', 1),
('Lớp KT01', 2, 'GV202', 'Hoạt động', 2),
('Lớp NN01', 3, 'GV203', 'Hoạt động', 3),
('Lớp KH01', 4, 'GV202', 'Hoạt động', 4),
('Lớp TN01', 5, 'GV201', 'Hoạt động', 5),
('Lớp CNTT02', 1, 'GV200', 'Hoạt động', 6),
('Lớp KT02', 2, 'GV203', 'Hoạt động', 7),
('Lớp NN02', 3, 'GV201', 'Hoạt động', 8),
('Lớp KH02', 4, 'GV202', 'Hoạt động', 9),
('Lớp TN02', 5, 'GV203', 'Hoạt động', 10);

INSERT INTO SinhVien (MaSV, TenSV, GioiTinh, NgaySinh, QueQuan, TrangThai) 
VALUES 
('200121', N'Phan Thị X', N'Nữ', '2000-10-25', N'Hải Dương', 'Đang học'),
('200122', N'Trần Văn Y', N'Nam', '2000-12-15', N'Bắc Ninh', 'Đang học'),
('200123', N'Hoàng Thị Z', N'Nữ', '2000-11-30', N'Hà Nam', 'Đang học'),
('200124', N'Vũ Văn W', N'Nam', '2000-09-20', N'Thái Bình', 'Đang học'),
('200125', N'Lê Thị K', N'Nữ', '2000-08-05', N'Hải Phòng', 'Đang học'),
('200126', N'Nguyễn Văn L', N'Nam', '2000-07-10', N'Nam Định', 'Đang học'),
('200127', N'Đặng Thị M', N'Nữ', '2000-06-22', N'Hưng Yên', 'Đang học'),
('200128', N'Trần Văn N', N'Nam', '2000-05-17', N'Thái Nguyên', 'Đang học'),
('200129', N'Phạm Thị O', N'Nữ', '2000-04-08', N'Bắc Giang', 'Đang học'),
('200130', N'Nguyễn Văn P', N'Nam', '2000-03-01', N'Quảng Ninh', 'Đang học');

INSERT INTO SinhVien_Lop (MaSV, MaLop) 
VALUES 
('200121', 1),  -- Sinh viên Phan Thị X thuộc lớp CNTT01
('200122', 2),  -- Sinh viên Trần Văn Y thuộc lớp KT01
('200123', 3),  -- Sinh viên Hoàng Thị Z thuộc lớp NN01
('200124', 4),  -- Sinh viên Vũ Văn W thuộc lớp KH01
('200125', 5),  -- Sinh viên Lê Thị K thuộc lớp TN01
('200126', 6),  -- Sinh viên Nguyễn Văn L thuộc lớp CNTT02
('200127', 7),  -- Sinh viên Đặng Thị M thuộc lớp KT02
('200128', 8),  -- Sinh viên Trần Văn N thuộc lớp NN02
('200129', 9),  -- Sinh viên Phạm Thị O thuộc lớp KH02
('200121', 10); -- Sinh viên Nguyễn Văn P thuộc lớp TN02

INSERT INTO Diem (MaSV, MaMH, HocKy, DiemTieuLuan, PhanTramDiemTieuLuan, DiemThi, PhanTramDiemThi) VALUES 
('200121', 1, 1, 7.5, 3, 8.0, 7),
('200122', 2, 1, 8.0, 3, 8.5, 7),
('200123', 3, 1, 6.5, 3, 7.0, 7),
('200124', 4, 1, 9.0, 3, 9.5, 7),
('200125', 5, 1, 8.5, 3, 9.0, 7);

INSERT INTO UsersSinhVien (username, password_hash, email, role) 
VALUES 
('200121', '123', 'user6@example.com', 'SinhVien'),
('200122', '123', 'user7@example.com', 'SinhVien'),
('200123', '123', 'user8@example.com', 'SinhVien'),
('200124', '123', 'user9@example.com', 'SinhVien'),
('200125', '123', 'user10@example.com', 'SinhVien'),
('200126', '123', 'user11@example.com', 'SinhVien'),
('200127', '123', 'user12@example.com', 'SinhVien'),
('200128', '123', 'user13@example.com', 'SinhVien'),
('200129', '123', 'user14@example.com', 'SinhVien'),
('200130', '123', 'user15@example.com', 'SinhVien');

INSERT INTO UsersGiaoVien (username, password_hash, email, role) 
VALUES 
('GV200', '123', 'teacher1@example.com', 'GiaoVien'),
('GV201', '123', 'teacher2@example.com', 'GiaoVien'),
('GV202', '123', 'teacher3@example.com', 'GiaoVien'),
('GV203', '123', 'teacher4@example.com', 'GiaoVien');

INSERT INTO Admin(MaAdmin, TenAdmin, DiaChi, DienThoai) VALUES (
	'admin01',
	'Nguyen Van A',
	'TpHCM',
	'123456789'
);

INSERT INTO UsersAdmin (username, password_hash, email, role) 
VALUES 
('admin01', '123', 'admin01@example.com', 'Admin')