using UnityEngine;
using UnityEngine.UI;
using System.Collections;

public class GPSManager : SingleTon<GPSManager> {

    //GPS 정보 표시 패널
    public GameObject gps_state_panel = null;
    private Text gps_state_text = null;

    //GPS 연결 정보
    private Define.Timer wait_timer = new Define.Timer(0, 10);  //GPS 대기 시간

    public enum GPSState
    {
        Connecting, //연결중
        Done,       //연결 완료
        Disable,    //연결 불가능
        TimeOut,    //대기 시간 지남
        ConnectFail  //연결 실패
    }
    private GPSState gps_state = GPSState.Connecting;

    //GPS 위치 정보
    private LocationInfo gps_info = new LocationInfo();
    [HideInInspector]
    public float latitude = 0f;
    [HideInInspector]
    public float longitude = 0f;

    //초기화
    void Start()
    {
        if (gps_state_panel != null)
            gps_state_text = gps_state_panel.transform.FindChild("Text").GetComponent<Text>();

        //테스트
        latitude = 37.45488f;
        longitude = 127.1308f;

        //if (latitude == 0)
            //StartCoroutine(GetGPS());
    }
    
    void Update()
    {
        //상태 표시 판넬이 있으면 표시
        if (gps_state_panel != null)
        {
            gps_state_text.text = GetGPSStateString();
        }
    }

    //GPS 찾기
    public void StartGetGPS()
    {
        //gps 판넬 켜기
        if (gps_state_panel != null)
            gps_state_panel.SetActive(true);

        //GPS 받기
        StartCoroutine(GetGPS());
    }

    //GPS 찾기 코루틴
    public IEnumerator GetGPS()
    {
        //연결중으로 표시
        gps_state = GPSState.Connecting;

        //gps 가능 여부
        //연결 불가능하면 진행X
        if (!Input.location.isEnabledByUser)
        {
            gps_state = GPSState.Disable;
            yield break;
        }

        //위치 받아오기 시작
        Input.location.Start();

        //초기화(GPS 연결) 되는거 기다리기
        while (Input.location.status == LocationServiceStatus.Initializing && wait_timer.CheckTimer())
        {
            yield return new WaitForSeconds(1);
        }

        //대기시간 지났으면 지났다 표시
        if (wait_timer.CheckTimer())
        {
            gps_state = GPSState.TimeOut;
            yield break;
        }

        //연결 실패시 진행X
        if (Input.location.status == LocationServiceStatus.Failed)
        {
            gps_state = GPSState.ConnectFail;
            yield break;
        }
        //연결 성공 시 GPS 정보 받기
        else
        {
            yield return new WaitForSeconds(1);
            gps_info = Input.location.lastData;
            latitude = gps_info.latitude;
            longitude = gps_info.longitude;
            gps_state = GPSState.Done;
        }

        //GPS 끝
        Input.location.Stop();
    }
    
    //GPS 상태
    public GPSState GetGPSState()
    {
        return gps_state;
    }

    //GPS 상태 문자열
    public string GetGPSStateString()
    {
        string send_string = "";

        switch (gps_state)
        {
            case GPSState.Connecting:
                send_string = "연결 중입니다";
                break;

            case GPSState.Done:
                if (gps_info.latitude == 0)
                    send_string = "연결을 확인해 주세요";
                else
                    send_string = "연결 성공";
                break;

            case GPSState.Disable:
                send_string = "연결이 불가능 합니다.";
                break;

            case GPSState.TimeOut:
                send_string = "대기 시간이 지났습니다";
                break;

            case GPSState.ConnectFail:
                send_string = "연결에 실패했습니다";
                break;
        }

        return send_string;
    }

    //GPS 정보
    public void SetGPSInfo(float _latitude, float _longitude)
    {
       latitude = _latitude;
       longitude = _longitude;
    }

    //누르면 정보창 끄기
    public void GPSinfoBtn()
    {
        gps_state_panel.SetActive(false);
    }

    //정보창 켜기
    public void ShowGPSState()
    {
        gps_state_panel.SetActive(true);
    }
}
