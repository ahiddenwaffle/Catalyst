# To anyone inspecting this folder for various fun things:  
## **THESE CANNOT BE USED WITH THE NORMAL API.  THEY DO NOT TAKE, NOR PROVIDE, KEYS.**


### Potential error codes for these endpoints:

#### Overall

- **99901** - No user is logged in
- **99902** - A user is already logged in

#### Email List

- **90001** - No email was passed
- **90002** - Invalid email was passed
- **90003** - No context was passed
- **90004** - An invalid context was passed

#### Login

- **90101** - No username was passed
- **90102** - Invalid username
- **90103** - The username does not exist
- **90104** - No password was passed
- **90105** - An incorrect password was passed
- **90106** - No CAPTCHA response was sent
- **90107** - An invalid CAPTCHA response was sent
- **90108** - This account has been suspended
- **90109** - This account has been deactivated
- **90110** - TOTP Challenge required

#### TOTP Login

- **90201** - There are no active TOTP logins
- **90202** - No code was passed
- **90203** - An invalid code was passed
- **90204** - The code is incorrect

#### Register

- **90301** - Username was not passed
- **90302** - Username is invalid
- **90303** - Username is already in use
- **90304** - Nickname is invalid
- **90305** - Email is invalid
- **90306** - Email is already in use
- **90307** - Password was not passed
- **90308** - Password does not meet requirements
- **90309** - Password confirmation was not passed
- **90310** - Password confirmation does not match the provided password
- **90311** - Color was not sent
- **90312** - Color is not a valid color
- **90313** - Profile picture is too large
- **90314** - Profile picture is not an image
- **90315** - Profile picture is invalid
- **90316** - NSFW profile picture checkbox is invalid
- **90317** - NSFW access checkbox is invalid
- **90318** - Terms of service agreement value was not sent
- **90319** - Terms of service was not agreed to
- **90320** - CAPTCHA was not completed
