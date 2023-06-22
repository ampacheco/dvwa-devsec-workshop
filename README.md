# 🎓 FortidevSec™ Fundamentals 

Welcome to FortiDevSec simple demo. A half-hour workshop to demonstrate how easy it can be to create a GitHub actions workflow that aggregates a security layer to your current DevOps practice.

## 👩‍💻 Pre-Requisites

1. Create a FortiDevSec Account 
1. A Github Account to [fork](https://github.com/ampacheco/dvwa-devsec-workshop/fork) this repository and runs github [actions](https://github.com/features/actions) 
1. An [Azure Subscriptions](https://azure.microsoft.com/en-us/free/) to Deploy [Damn Vulnerable Web Application](https://github.com/digininja/DVWA) canonical app to demonstrate DAST feature. In this particular case, it deployed on [Azure Container Instances Service](https://azure.microsoft.com/en-us/products/container-instances).
<p><br/>
    
## 📋 Table of contents

<img src="https://avatars.githubusercontent.com/u/39314919?s=200&v=4" align="right" width="300px"/>

1. [Objectives](#1-objectives)
1. [TheFortiDevSec UI](#2-the-fortidevsec-ui)
1. [The DevOps Workflow ](#3-the-devops-workflow)
1. [The SAST Job](#4-the-sast-job)
1. [Deploy the Application to ACI](#5-deploy-the-aplication-to-aci)
1. [The Dast Job](#6-the-dast-job)

<p><br/>
    
## 1. Objectives
   
1️⃣ Introduce FortiDevSec as a simple tool to add a security layer to your current continuous application development workflow<p/>
2️⃣ If you have never used it, introduce GitHub actions as a simple workflow engine to manage the complete lifecycle<p/>
3️⃣ Describe and explain the configuration yaml file required to connect your workflow engine with FortiDevSec<p/>
4️⃣ Describe and explain the steps required to implement SAST and DAST scans.

🚀 **Have fun with an interactive session**

📋 [Go to Table of Contents](#-table-of-contents)

# 🏁 Start Hands-on

## 2. The FortiDevSec UI

FortiDevSec is an  application security testing product that offers a comprehensive SaaS based continuous application testing for software developers and devops, without the need for any security expertise.


Navigate to the [Live Demo](https://fortidevsec.forticloud.com/#/secOpsDashboard) to familiarize yourself with the Basic UI, and explore the main dashboard and then the application dashboard.

👉 [Explore Live Demo](https://fortidevsec.forticloud.com/#/secOpsDashboard)

📋 [Go to Table of Contents](#-table-of-contents)

### 🟩The Basic Workflow

![The Basic Workflow](/images/the-basic-workflow.png)

### 🟩Summary of Scanners

|Sacanner|Description|
|---|---|
|**SAST**|Scans the source code of an application during development to minimize zero-day vulnerabilities. The application languages supported for SAST are Shell, Java, Ruby on Rails, Python, Golang, PHP, JavaScript/NodeJS, C and C++.|
|**DAST**|Scans a deployed application at runtime to detect vulnerabilities. The DAST scanner supports scanning of assets/targets hosted on both the internal network of an organization and the external/public network using FortiDAST proxy server. See FortiDAST Proxy Server.|
|**SCA**|Scans for vulnerabilities in the open-source libraries/components used by the application. The programming languages supported by the SCA scanner are Java, Javascript, Ruby, Python, Golang, and PHP.|
_Others Scanneres ..._
|**Sacanner**|**Description**|
|---|---|
|**Secret**|Scans to detect certificates and other secrets committed into Git.|
|**IaC**|Scans your IaC configuration files for Terraform, Cloud Formation, Docker and Kubernetes, to detect configuration issues.|
|**Container**|Scans container components to identify potential vulnerabilities.|

### 🟩The UI
![The UI](/images/the-basic-ui.png)

📋 [Go to Table of Contents](#-table-of-contents)

## 3. The DevOps Workflow

The number of jobs and steps per job you will end up having in your process depends on the maturity of your current practice. 

These hands-on labs explore how to add SAST and DAST scanner analisys to your current workflow if you have one or bring a ground foundation for a start a DevOps practice with security from the starting point. 

- Build
- Test - ... as many as you have incorporated into your practice and SAST
- Deploy
- DAST 

In this step, we will create the skeleton of the basic Github actions workflow jobs required to complete our job successfully. 

After completing this step, you will be adding steps per job to incorporate: 

1. SAST
1. Deployment of our application to Azure as Azure Container Instance
1. Run DAST Analysis 

For those able to understand the basic workflow, I recommend copying and pasting the yaml in the box into the ./github/workflow/main.yaml file.

To simplify your start, the main file in the repo contains this yaml code. So you will complete the copying/pasting cycling in the following steps as required.


````
name: 🚀🚀 Basic 👨‍💻Dev - Sec🪲 Ops📉  Workflow 🚀🚀  
on:
  push:
    branches: [ "main" ]
  pull_request:
    branches: [ "main" ]

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
    - name: Build Workflow
      run: echo "🏗️ Dummy Step, compile, package, create container for application and ..."

  test:
    runs-on: ubuntu-latest
    needs: build
    
    steps:
    - name: sast
      run: echo "🏗️ test, job sast step"

  deploy:
    runs-on: ubuntu-latest
    needs: test

    steps:
    - name: Deploy application in azure container
      run: echo "🏗️ Deploying ..."

  dast:
    runs-on: ubuntu-latest
    needs: deploy

    steps:
    - name: DAST analysis
      run: |
        echo "🏗️ Runing DAST Analysis"
````

Remember that this step was completed for you,  so edit the file to familiarize yourself with the process by adding a simple comment to the beginning of the file. 
````
# Actions Workflow setup completed
````
After updating the main.yml file, workflow is triggered. Then you go to the actions tab [1], click workflow name [2], and click the last run details[3].  
![](/images/actions.png)

If everything runs successfully you should see something like that. 
![](/images/basic-workflow-created.png)

📋 [Go to Table of Contents](#-table-of-contents)

## 4. The SAST Job

It is out of our current scope to build, containerize and push the created container to a registry. For simplicity's sake, I will keep the job empty, and you can assume that the job was completed with the right tasks, and I will start our next step, which should be adding the SAST step to our workflow.  

It is out of our current scope to build, containerize and push the created container to a registry. For simplicity's sake, I will keep the job empty, and you can assume that the job was completed with the right tasks, and I will start our next step, which should be adding the SAST step to our workflow.  

To complete SAST task, you need to add to your test job the next step.

````
  test:
    runs-on: ubuntu-latest
    needs: build
    
    # All Other Steps
     
    # SAST STEPS
    – name: SAST
      run: |
      env | grep -E "GITHUB_ACTIONS|GITHUB_RUN_NUMBER|GITHUB_REF_NAME|GITHUB_SHA" > /tmp/env
      docker pull registry.fortidevsec.forticloud.com/fdevsec_sast:latest
      docker run --rm --env-file /tmp/env --mount type=bind,source=$PWD,target=/scan registry.fortidevsec.forticloud.com/fdevsec_sast:latest
````

After completing the edition, your complete workflow should be something like that. Note I am adding a green check box to the tasks completed.

````
name: 🚀🚀 Basic 👨‍💻Dev - Sec🪲 Ops📉  Workflow 🚀🚀  
on:
  push:
    branches: [ "main" ]
  pull_request:
    branches: [ "main" ]

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
    - name: ✅ Build Workflow
      run: echo "🏗️ Dummy Step, compile, package, create container for application and ..."

  test:
    runs-on: ubuntu-latest
    needs: build

    # SAST STEPS
    – name: ✅ SAST
      run: |
      env | grep -E "GITHUB_ACTIONS|GITHUB_RUN_NUMBER|GITHUB_REF_NAME|GITHUB_SHA" > /tmp/env
      docker pull registry.fortidevsec.forticloud.com/fdevsec_sast:latest
      docker run --rm --env-file /tmp/env --mount type=bind,source=$PWD,target=/scan registry.fortidevsec.forticloud.com/fdevsec_sast:latest

  deploy:
    runs-on: ubuntu-latest
    needs: test

    steps:
    - name: Deploy application in azure container
      run: echo "🏗️ Deploying ..."

  dast:
    runs-on: ubuntu-latest
    needs: deploy

    steps:
    - name: DAST analysis
      run: |
        echo "🏗️ Runing DAST Analysis"
````
📋 [Go to Table of Contents](#-table-of-contents)

## 5. Deploy the aplication to ACI

Steps to Deploy Application as Azure Container Instances.
<p/><br/>

### 🟩5.1 Using a [guid](https://guidgenerator.com/online-guid-generator.aspx) generator online tool, you will create a unique name for DNS required to deploy your app. We will store it as a variable in  your GitHub Project|Settings|Security| Secrets and Variables

![Variables](/images/variables.png)

### 🟩5.2 Run the following command to get the Azure service principal to run Azure cli commands in GitHub Actions

🟦Command
````
az ad sp create-for-rbac --name "myApp" --role contributor --scopes /subscriptions/{subscription-id}/resourceGroups/{resource-group} --sdk-auth
````
🟦Output

````
 {
   "clientId": "<GUID>",
   "clientSecret": "<STRING>",
   "subscriptionId": "<GUID>",
   "tenantId": "<GUID>",
   "resourceManagerEndpointUrl": "<URL>"
   (...)
 }

````
Store the variables as secrets in your GitHub Project|Settings|Security| Secrets and Variables.

|VAR|VALUE|
|---|----|
|**CLIENT_ID**|clientId|
|**CLIENT_SECRET**|clientSecret|
|**SUBSCTIPTION_ID**|subscriptionId|
|**TENANT_ID**|tenantId|

![Secrets](/images/secrets.png)

### 🟩5.3 
Now you can complete the deploy workflow, adding the following two steps and an additional job to destroy the Microsoft Azure container instance created. 

The complete workflow should be the following.
````
name: 🚀🚀 Basic 👨‍💻Dev - Sec🪲 Ops📉  Workflow 🚀🚀  
on:
  push:
    branches: [ "main" ]
  pull_request:
    branches: [ "main" ]

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
    - name: ✅ Build Workflow
      run: echo "✅ Dummy Step, compile, package, create container for application and ..."

  test:
    runs-on: ubuntu-latest
    needs: build

    steps:
    - name: ✅ Checkout
      uses: actions/checkout@v2

    - name: ✅ SAST
      run: |
        env | grep -E "GITHUB_ACTIONS|GITHUB_RUN_NUMBER|GITHUB_REF_NAME|GITHUB_SHA" > /tmp/env
        docker pull registry.fortidevsec.forticloud.com/fdevsec_sast:latest
        docker run --rm --env-file /tmp/env --mount type=bind,source=$PWD,target=/scan registry.fortidevsec.forticloud.com/fdevsec_sast:latest
    
  deploy:
    runs-on: ubuntu-latest
    needs: test

    steps:
    - name: ✅ Azure Login
      uses: azure/login@v1
      with:
        creds: '{"clientId":"${{ secrets.CLIENT_ID }}","clientSecret":"${{ secrets.CLIENT_SECRET }}","subscriptionId":"${{ secrets.SUBSCRIPTION_ID }}","tenantId":"${{ secrets.TENANT_ID }}"}'
            
    - name: ✅ Deploy the App
      uses: Azure/cli@v1.0.7
      with:
        azcliversion: 2.30.0
        inlineScript: | 
          az group create -n ${{ vars.AZ_ACI_DNS_PREFIX }}-rg -l eastus
          az container create --name ${{ vars.AZ_ACI_DNS_PREFIX }} --dns-name-label ${{ vars.AZ_ACI_DNS_PREFIX }} --image vulnerables/web-dvwa -g ${{ vars.AZ_ACI_DNS_PREFIX }}-rg
    
  dast:
    runs-on: ubuntu-latest
    needs: deploy

  clean-up-az:
    runs-on: ubuntu-latest
    needs: dast

    steps:
    - name: ✅ Azure Login
      uses: azure/login@v1
      with:
        creds: '{"clientId":"${{ secrets.CLIENT_ID }}","clientSecret":"${{ secrets.CLIENT_SECRET }}","subscriptionId":"${{ secrets.SUBSCRIPTION_ID }}","tenantId":"${{ secrets.TENANT_ID }}"}'
            
    - name: ✅ Clean Up Azure Container Instances App
      uses: Azure/cli@v1.0.7
      with:
        azcliversion: 2.30.0
        inlineScript: | 
          az group delete -n ${{ vars.AZ_ACI_DNS_PREFIX }}-rg -y
  
````
📋 [Go to Table of Contents](#-table-of-contents)

## 6. The DAST Job

Once we have an application in a pre-production environment, we can add the DAST job, and after updating the main.yml file, you will trigger a new run. 
Check the DAST job on the following file and copy and paste it into your current main.yml file.

````
name: 🚀🚀 Basic 👨‍💻Dev - Sec🪲 Ops📉  Workflow 🚀🚀  
on:
  push:
    branches: [ "main" ]
  pull_request:
    branches: [ "main" ]

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
    - name: ✅ Build Workflow
      run: echo "✅ Dummy Step, compile, package, create container for application and ..."

  test:
    runs-on: ubuntu-latest
    needs: build

    steps:
    - name: ✅ Checkout
      uses: actions/checkout@v2

    - name: ✅ SAST
      run: |
        env | grep -E "GITHUB_ACTIONS|GITHUB_RUN_NUMBER|GITHUB_REF_NAME|GITHUB_SHA" > /tmp/env
        docker pull registry.fortidevsec.forticloud.com/fdevsec_sast:latest
        docker run --rm --env-file /tmp/env --mount type=bind,source=$PWD,target=/scan registry.fortidevsec.forticloud.com/fdevsec_sast:latest
    
  deploy:
    runs-on: ubuntu-latest
    needs: test

    steps:
    - name: ✅ Azure Login
      uses: azure/login@v1
      with:
        creds: '{"clientId":"${{ secrets.CLIENT_ID }}","clientSecret":"${{ secrets.CLIENT_SECRET }}","subscriptionId":"${{ secrets.SUBSCRIPTION_ID }}","tenantId":"${{ secrets.TENANT_ID }}"}'
            
    - name: ✅ Deploy the App
      uses: Azure/cli@v1.0.7
      with:
        azcliversion: 2.30.0
        inlineScript: | 
          az group create -n ${{ vars.AZ_ACI_DNS_PREFIX }}-rg -l eastus
          az container create --name ${{ vars.AZ_ACI_DNS_PREFIX }} --dns-name-label ${{ vars.AZ_ACI_DNS_PREFIX }} --image vulnerables/web-dvwa -g ${{ vars.AZ_ACI_DNS_PREFIX }}-rg
    
  dast:
    runs-on: ubuntu-latest
    needs: deploy
    
    steps:
    - name: ✅ Checkout
      uses: actions/checkout@v2

    - name: ✅ DAST
      run: | 
        env | grep -E "GITHUB_ACTIONS|GITHUB_RUN_NUMBER|GITHUB_REF_NAME|GITHUB_SHA" > /tmp/env
        docker pull registry.fortidevsec.forticloud.com/fdevsec_dast:latest
        docker run --rm --env-file /tmp/env --mount type=bind,source=$PWD,target=/scan registry.fortidevsec.forticloud.com/fdevsec_dast:latest

  clean-up-az:
    runs-on: ubuntu-latest
    needs: dast

    steps:
    - name: ✅ Azure Login
      uses: azure/login@v1
      with:
        creds: '{"clientId":"${{ secrets.CLIENT_ID }}","clientSecret":"${{ secrets.CLIENT_SECRET }}","subscriptionId":"${{ secrets.SUBSCRIPTION_ID }}","tenantId":"${{ secrets.TENANT_ID }}"}'
            
    - name: ✅ Clean Up Azure Container Instances App
      uses: Azure/cli@v1.0.7
      with:
        azcliversion: 2.30.0
        inlineScript: | 
          az group delete -n ${{ vars.AZ_ACI_DNS_PREFIX }}-rg -y
  
````

📋 [Go to Table of Contents](#-table-of-contents)

**... and see you at our next workshop!**
> Sincerely yours, The BDE Team

 
