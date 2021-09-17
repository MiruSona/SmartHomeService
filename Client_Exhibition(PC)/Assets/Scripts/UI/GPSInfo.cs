using UnityEngine;
using UnityEngine.UI;
using System.Collections;

public class GPSInfo : MonoBehaviour {

    //데이터
    private GPSManager gps_manager;

    //컴포넌트
    private Text state, latitude, longitude;

    //초기화
    void Start()
    {
        //데이터
        gps_manager = GPSManager.instance;

        //컴포넌트
        state = transform.FindChild("Info").FindChild("State").GetComponent<Text>();
        latitude = transform.FindChild("Info").FindChild("Latitude").GetComponent<Text>();
        longitude = transform.FindChild("Info").FindChild("Longitude").GetComponent<Text>();
    }

    //데이터 확인 및 표시
    void Update()
    {
        //데이터가 없으면 기다리라 표시
        if (gps_manager.GetGPSState() == GPSManager.GPSState.Connecting && gps_manager.latitude == 0)
        {
            state.text = gps_manager.GetGPSStateString();
            latitude.text = "";
            longitude.text = "";
        }
        //아니면 텍스트 갱신
        else
        {
            RenewTexts();
        }
    }

    //텍스트 갱신
    private void RenewTexts()
    {
        state.text = gps_manager.GetGPSStateString();
        if(gps_manager.latitude != 0)
        {
            latitude.text = gps_manager.latitude.ToString();
            longitude.text = gps_manager.longitude.ToString();
        }
        else
        {
            latitude.text = "";
            longitude.text = "";
        }
        
    }

    //스스로 꺼지기
    public void DisableMe()
    {
        gameObject.SetActive(false);
    }

    //갱신 버튼
    public void RefreshBtn()
    {
        gps_manager.StartGetGPS();
    }
}
