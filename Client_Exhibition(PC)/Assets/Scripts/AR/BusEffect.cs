using UnityEngine;
using System.Collections;

public class BusEffect : MonoBehaviour {

    //데이터
    private BusManager bus_manager;

    //참조
    private GameObject effect_now;
    private GameObject effect_next;

    //컴포넌트
    private TargetManager target_manager;
    
    //bool값
    private bool bus_next = false;

    //초기화
    void Start()
    {
        bus_manager = BusManager.instance;
        effect_now = transform.FindChild("BusNow").gameObject;
        effect_next = transform.FindChild("BusNext").gameObject;
        target_manager = GetComponent<TargetManager>();
    }

    //이펙트 ON/OFF
    void Update()
    {
        //타겟 인식 확인
        if (target_manager.GetDetected())
        {
            //데이터가 있으면 이펙트 토글
            if (bus_manager.bus_info_select)
                ToggleEffect();
        }
        //아니면 전부 끄기
        else
        {
            effect_now.SetActive(false);
            effect_next.SetActive(false);
        }
    }

    //이펙트 토글
    private void ToggleEffect()
    {
        //bool값따라 변경
        if (!bus_next)
        {
            effect_now.SetActive(true);
            effect_next.SetActive(false);
        }
        else
        {
            effect_now.SetActive(false);
            effect_next.SetActive(true);
        }
    }

    //bool값 토글
    public void ToggleBusNext()
    {
        if (bus_next)
            bus_next = false;
        else
            bus_next = true;
    }
}
