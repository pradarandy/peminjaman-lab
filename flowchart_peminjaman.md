# Flowchart Sistem Peminjaman Berjenjang

Berikut adalah diagram flowchart untuk alur peminjaman dari user mengakses sistem hingga proses approval berdasarkan level (Level 1, Level 2, dan Level 3).

```mermaid
flowchart TD
    %% Styling
    classDef startEnd fill:#f9f,stroke:#333,stroke-width:2px;
    classDef process fill:#bbf,stroke:#333,stroke-width:2px;
    classDef decision fill:#ffb,stroke:#333,stroke-width:2px;
    classDef success fill:#9f9,stroke:#333,stroke-width:2px;
    classDef reject fill:#f99,stroke:#333,stroke-width:2px;

    %% Nodes
    Start([User Mengakses Sistem]) ::: startEnd
    IsiForm[Membuat Pengajuan Peminjaman] ::: process
    CekLevel{Identifikasi Level Peminjaman \n (Lvl 1 / Lvl 2 / Lvl 3)} ::: decision
    
    Approve1{Approval Level 1} ::: decision
    CekLanjut2{Apakah Butuh \n Approval Level 2?} ::: decision
    
    Approve2{Approval Level 2} ::: decision
    CekLanjut3{Apakah Butuh \n Approval Level 3?} ::: decision
    
    Approve3{Approval Level 3} ::: decision
    
    Success([Peminjaman Disetujui]) ::: success
    Reject([Peminjaman Ditolak]) ::: reject

    %% Flow
    Start --> IsiForm
    IsiForm --> CekLevel
    CekLevel --> Approve1
    
    %% Level 1 Flow
    Approve1 -- Ditolak --> Reject
    Approve1 -- Disetujui --> CekLanjut2
    
    %% Lanjut ke Level 2 atau Selesai di Level 1
    CekLanjut2 -- Tidak \n (Hanya Lvl 1) --> Success
    CekLanjut2 -- Ya \n (Lvl 2 / Lvl 3) --> Approve2
    
    %% Level 2 Flow
    Approve2 -- Ditolak --> Reject
    Approve2 -- Disetujui --> CekLanjut3
    
    %% Lanjut ke Level 3 atau Selesai di Level 2
    CekLanjut3 -- Tidak \n (Hanya Lvl 2) --> Success
    CekLanjut3 -- Ya \n (Lvl 3) --> Approve3
    
    %% Level 3 Flow
    Approve3 -- Ditolak --> Reject
    Approve3 -- Disetujui --> Success
```
