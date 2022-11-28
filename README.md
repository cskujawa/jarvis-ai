<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="https://github.com/cskujawa/jarvis-ai">
    <img src="https://github.com/cskujawa/jarvis-ai/blob/main/interface/laravel/public/image/logo.png" alt="Logo">
  </a>

  <p align="center">
    <a href="https://github.com/othneildrew/Best-README-Template/issues">Report Bug</a>
    ·
    <a href="https://github.com/othneildrew/Best-README-Template/issues">Request Feature</a>
  </p>
</div>



<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
  </ol>
</details>


<!-- ABOUT THE PROJECT -->
## About The Project
<img src=https://github.com/cskujawa/jarvis-ai/blob/main/interface/laravel/public/image/app.png alt="App Example">

### :warning: :warning: :warning: Disclaimer :warning: :warning: :warning:
This documentation and the project as a whole are a work in progress and I will likely be switching to a different repo to continue development.

J.A.R.V.I.S. is just a really versatile information system.

This project is essentially a scaffolding for much more.

The front end implements a simple Laravel server utilizing the Laravel-Breeze login and Tailwind CSS scaffolding.

The back end relies on NGINX, MySQL, Prometheus, CAdivsor, Redis, Docker, and Docker-Compose.

<p align="right">(<a href="#top">back to top</a>)</p>


### Built With

Laravel provides a simple webserver setup and with the easily installable Breeze package it's a website wrapped up and ready to go. Lravel depends on MySQL for storing data.

Grafana implements graphing nirvana but requires data sources to be connected. This is where Prometheus comes in, it is able to scrape data and format it. CAdvisor is able to collect the data from various sources and store it in a Redis container.

NGINX is what allows all of the containers to commuincate with each other.

* [![Laravel][Laravel.com]][Laravel-url]
* [![MySQL][Mysql.com]][Mysql-url]
* [![Grafana][Grafana.com]][Grafana-url]
* [![Prometheus][Prometheus.io]][Prometheus-url]
* [![Redis][Redis.io]][Redis-url]
* [![NGINX][NGINX.com]][Nginx-url]
* [![CAdvisor][Github.com/google/cadvisor]][Cadvisor-url]

<p align="right">(<a href="#top">back to top</a>)</p>



<!-- GETTING STARTED -->
## Getting Started

This is a standalone environment that runs on Docker Compose and the monitoring requires Prometheus and Node Exporter to be installed on the host OS. None of the containerized apps require any dependencies to be pre-installed or exist on the host OS. That all being said, if you have Docker Compose you should be able to clone or fork this repo, change to the project directory and boot it up.

### Ubuntu Server Setup
[Ubuntu Server Guide](https://github.com/cskujawa/jarvis-ai/blob/main/docs/ubuntu-server-config/README.md)

### WSL Setup
[WSL Guide](https://github.com/cskujawa/jarvis-ai/tree/main/docs/wsl-config)

<!-- CONTRIBUTING -->
## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also simply open an issue with the tag "enhancement".
Don't forget to give the project a star! Thanks again!

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

<p align="right">(<a href="#top">back to top</a>)</p>

<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->

[Laravel.com]: https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white
[Laravel-url]: https://laravel.com
[Mysql.com]: https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white
[Mysql-url]: https://mysql.com
[Grafana.com]: https://img.shields.io/badge/grafana-%23F46800.svg?style=for-the-badge&logo=grafana&logoColor=white
[Grafana-url]: https://grafana.com
[Prometheus.io]: https://img.shields.io/badge/Prometheus-E6522C?style=for-the-badge&logo=Prometheus&logoColor=white
[Prometheus-url]: https://prometheus.io
[Redis.io]: https://img.shields.io/badge/redis-%23DD0031.svg?style=for-the-badge&logo=redis&logoColor=white
[Redis-url]: https://Redis.io
[NGINX.com]: https://img.shields.io/badge/nginx-%23009639.svg?style=for-the-badge&logo=nginx&logoColor=white
[Nginx-url]: https://nginx.com
[Github.com/google/cadvisor]: https://img.shields.io/badge/CAdvsior-CAdvsior-yellowgreen
[Cadvisor-url]: https://github.com/google/cadvisor
[JQuery.com]: https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white
[JQuery-url]: https://jquery.com 
