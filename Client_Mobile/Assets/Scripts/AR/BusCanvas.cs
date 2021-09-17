using UnityEngine;
using UnityEngine.UI;
using System.Collections;

public class BusCanvas : MonoBehaviour {

    //데이터
    private BusManager bus_manager;

    //타겟
    public BusEffect target;
    private TargetManager target_manager;

    //컴포넌트
    private GameObject ar_canvas;
    private Text ar_text;

    //다음버스여부
    private bool next_bus = false;
    
    //초기화
    void Start()
    {
        //데이터
        bus_manager = BusManager.instance;

        //타겟매니져 받기
        target_manager = target.GetComponent<TargetManager>();

        //기본적으로 UI꺼두기
        ar_canvas = transform.FindChild("ARCanvas").gameObject;
        ar_canvas.gameObject.SetActive(false);

        //텍스트 연결
        ar_text = ar_canvas.transform.FindChild("ARText").GetComponent<Text>();
    }

    //ARCanvas 처리
    void Update()
    {
        //타겟이 인식됬다면
        if (target_manager.GetDetected())
        {
            //UI켜기
            if (!ar_canvas.activeSelf)
                ar_canvas.SetActive(true);

            //UI이동
            Vector3 pos = target.transform.localPosition;
            pos.z -= 30.0f;
            transform.localPosition = pos;

            //텍스트 표시
            ChangeText();
        }
        //타겟이 벗어났으면 UI끄기
        else
            ar_canvas.SetActive(false);
    }

    //텍스트 변경
    private void ChangeText()
    {
        //버스 선택해놓은지 확인
        if (bus_manager.bus_info_select)
        {
            //어떤 버스인지에 따라 표시
            if (!next_bus)
            {
                ar_text.text =
                "현재 버스:" + bus_manager.bus_name + "\n" +
                "도착 시간:" + bus_manager.predict_time[0];
            }
            else
            {
                ar_text.text =
                "다음 버스:" + bus_manager.bus_name + "\n" +
                "도착 시간:" + bus_manager.predict_time[1];
            }

        }
        //아니면 버스 선택해라 표시
        else
        {
            ar_text.text = "설정창에서 버스를 선택해 주세요!";
        }
    }

    //AR버튼
    public void ARBtn()
    {
        //인덱스 변경
        if (next_bus)
            next_bus = false;
        else
            next_bus = true;

        //이펙트 변경
        target.ToggleBusNext();
    }
}
