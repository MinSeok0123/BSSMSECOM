# 1. 기획의도
- 웹을 통해 기숙사 방 문을 원격으로 쉽게 열 수 있습니다.
- 일과시간 내에 외부 출입자를 감지하고, 해당 정보를 기록할 수 있습니다.
- 기숙사의 온도와 습도를 실시간으로 모니터링할 수 있습니다.
---

# 2. 프로젝트 구성
## 하드웨어 구성
### 1. 모션 감지 센서:
- 사람의 출입을 감지하고, 해당 출입시간을 데이터베이스에 기록합니다.
- 학교의 일과시간 중에 출입이 감지되면 부저를 울리고 웹으로 경고 메시지를 전송합니다.
### 2. 온/습도 센서:
- 기숙사 방 안의 온도와 습도를 실시간으로 측정합니다.
- 웹에서 실시간으로 이 정보를 확인할 수 있습니다.
### 3. 서보모터:
- 웹에서 열림 버튼을 누르면 기숙사 방 내의 서보모터를 제어하여 원격으로 문을 열 수 있습니다.
### 4. 부저:
- 정해진 시간 외에 출입이 감지되면 소리를 시끄럽게 울립니다.
### 5. LCD 디스플레이:
- 와이파이에 연결되면 IP 주소를 디스플레이에 표시합니다.
- 온도와 습도 정보를 디스플레이에 표시하여 실시간 모니터링이 가능합니다.
---

## 소프트웨어 구성
### DB : MYSQL
### SERVER : PHP
### WEB : HTML/CSS/JAVASCRIPT
---
### 1. 홈페이지:
- DB에서 페이지 3개의 최근 값을 가져와 네비게이션에 표시해 줍니다.
- ajax를 이용해 5초 마다 값을 새로 고침해서 DB에서 실시간으로 값을 불러옵니다.
![](https://velog.velcdn.com/images/minseok0123/post/ce6d51a7-162e-4eee-8ce9-dc1d4bb7094e/image.png)
### 2. 기숙사 상태
- ajax를 이용해 5초 마다 값을 새로 고침해서 DB에서 실시간으로 값을 불러옵니다.
- 현재 기숙사의 온/습도를 ESP32에서 보낸 값을 DB에 저장하고 불러옵니다.
![](https://velog.velcdn.com/images/minseok0123/post/fc2c3885-2def-4c92-9d68-2ff59db80bfb/image.png)
### 3. 출입자 관리
- 전체 보기를 누르면 입과 출입 여부 상관없이 전체 시간대의 출입 내용을 모두 보여준다.
- 일과 출입 여부를 누르면 일과시간 출입인 값만 필터링해서 가져온다.
- 데이터값이 많으면 자동으로 페이지네이션 된다.
- 최근 업데이트를 만들어서 쉽게 몇 분, 몇 시, 며칠 전에 출입했는지 확인할 수 있다.
- ajax를 이용해 5초 마다 값을 새로 고침해서 DB에서 실시간으로 값을 불러옵니다.
![](https://velog.velcdn.com/images/minseok0123/post/0ce3c217-21f9-4d39-a008-a8e482481f81/image.png)
---
### 3-1 일과시간 수정
- 일과시간 수정 버튼을 누르면 일과시간을 수정할 수 있는 모달창이 뜬다.
- 등교시간/하교시간을 수정하고 저장 버튼을 누르면 ESP32웹 서버에 GET으로 값이 전달된다.
- DB에도 값이 업데이트 되서 필터링 되는 조건이 변경이 된다.
![](https://velog.velcdn.com/images/minseok0123/post/21528970-57a0-4aad-9148-8f9087b963f2/image.png)

### 4. 원격제어
- ESP32 웹 서버에 OPEN이라는 값을 GET으로 넘긴다.
- 값이 들어오면 서보모터를 작동시킨다.
![](https://velog.velcdn.com/images/minseok0123/post/e3637ae3-289e-4bb4-91e7-5a8e5b4ab15a/image.png)
### 5. 로그인
- 디바이스를 할당하기 위해서 계정이 필요했다.
- 그래서 로그인/회원가입을 만들기로 했다.
![](https://velog.velcdn.com/images/minseok0123/post/ac7823d8-5fa9-46df-b8bc-45ff17002a7b/image.png)
### 6. 회원가입
- 아이디를 입력하고 중복확인을 누르면 DB에 중복되는 아이디가 있는지 확인하다.
- 비밀번호를 4자리 이상으로 입력해야 한다. (비밀번호는 해시로 암호화 한다)
- 회원가입 할 때 ESP32의 웹 서버 IP주소를 입력하면 디바이스가 할당된다.
- 이메일도 중복되지 않아야지 가입이 된다.
![](https://velog.velcdn.com/images/minseok0123/post/2d81404b-9864-4b47-a458-219663ecf6ef/image.png)

### 7. 비밀번호 찾기
- 로그인을 만들면서 만약 비밀번호를 까먹으면 새로 가입해야 하는 번거로움이 생겼다.
만약 새로 가입하지 않을려면 DB를 직접 수정해야 했다.
- 그래서 비밀번호 찾기를 만들기로 결정했다.
- PHP MAILER를 이용해서 이메일로 비밀번호 재설정 코드를 보내줬다.
![](https://velog.velcdn.com/images/minseok0123/post/50dfb2ec-9d15-4d1b-a8bc-7123679badfb/image.png)
- 이렇게 재설정 인증 코드가 이메일로 온다.
![](https://velog.velcdn.com/images/minseok0123/post/dcfe5cc8-3758-45d0-b683-0822becfc991/image.png)
- 인증 코드를 입력하면가 일치하면 비밀번호가 재설정 된다. 
![](https://velog.velcdn.com/images/minseok0123/post/37c4021a-f665-4385-b74b-e7c97e50f318/image.png)
---
# 3. 기대효과

- 1. 웹을 통한 원격 문 열기로 인해 출입의 편의성이 향상됩니다.
- 2. 외부 출입자의 감지와 출입 기록으로 보안 강화와 출입 관리가 용이해집니다.
- 3. 기숙사 내 온도와 습도를 실시간으로 모니터링하여 안전하고 편리한 환경을 조성합니다.
---

# 4. 마무리

> 이 프로젝트는 스마트 기숙사 시스템으로 원격 문 열기와 출입 감지, 온/습도 모니터링 등을 제공합니다. 다양한 센서와 LCD 디스플레이를 활용하여 학생들의 안전과 편의성을 향상시킬 수 있습니다. 이 프로젝트는 현대적인 기술과 창의적인 아이디어를 결합하여 효율적인 기숙사 관리 시스템을 구축하는 데 성공하였습니다.

**GITHUB**
> https://github.com/MinSeok0123/BSSMSECOM
