# Docker Image ebooks-fix
Erzeugt ein Docker Image das eine Webanwendung zur Bereinigung von MARC-Dateien zur Verfügung stellt.

* Benötigt das Repository ___catmandu-marc___ (Benutzer ___ublast___), das ein Docker Image mit allen benötigten perl Modulen für __Catmandu__ und __Catmandu::MARC__ paketiert.
* Beinhaltet als Untermodul das von ___georgd___ geforkte Modul ___ebooks___, welches ein Perlskript zum bequemen Aufruf von Catmandu und von ihm gewartete Korrekturregeln enthält.
* Die Umwandlung wird über ein einfaches Webinterface vorgenommen.
* Ein Webserver kann dem Dockercontainer vorgeschaltet werden, der dieses Service nach außen weiterreichen kann.

## Docker Image bauen
* Zuerst das Docker Image ___catmandu-marc___ lokal bauen:
  * In das Verzeichnis des Repositories ___catmandu-marc___ wechseln:  
    `cd catmandu-marc`
  * Ausführen:  
    `docker build  -f ./Dockerfile -t catmandu-marc .`

* Das Docker Image ___ebooks-fix___ bauen:
  * In das Verzeichnis des Repositories ___ebooks-fix___ wechseln:  
    ` cd ebooks-fix`
  * Aktuellen Stand des eingebundenen Submodules ___ebooks___ holen:  
    `git submodule update --init --recursive --remote`
  * Ausführen:  
    `docker build -f ./Dockerfile -t ebooks-fix .`

## Instanzieren des Images
* Starten des Containers:  
  `docker run -p 8080:80 --name ebooks-fix --rm ebooks-fix &`
* Beenden des Containers:  
  `docker stop ebooks-fix`

* ___Anmerkung___: Durch `-p 8080:80` wird der im Dockercontainer verwendete Port `80` nach außen auf Port `8080` gemappt.

## Debugging des Images
* Starten des Containers:  
  `docker run -p 8080:80 --name ebooks-fix --entrypoint "" -it --rm ebooks-fix /bin/bash`
* In der Kommandozeile des Dockercontainers:
  * Apache starten:  
    `/usr/sbin/httpd`
  * Logdatei des Apaches mitlesen:  
    `tail -f /var/log/httpd/error_log`
  * Beenden des Containers:  
    `exit`

## Weiterleitung von einem vorgeschalteten Apache

```
<Location /ebooks-fix/>
    ProxyPreserveHost On
    ProxyPass               http://localhost:8080/ebooks-fix/
    ProxyPassReverse        http://localhost:8080/ebooks-fix/
    # no restrictions
    require all granted
    # restrictions
    ## require ip <some ip>
    ## require ip <some net>/24
</Location>
```

