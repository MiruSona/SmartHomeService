using UnityEngine;
using UnityEngine.UI;
using System.Collections;

public class DropdownBtn : MonoBehaviour {

    //참조
    public GameObject scroll_view;
    private Text text;

    //엑티브 여부
    [HideInInspector]
    public bool active = false;

    //인덱스(현재 누른 버튼)
    [HideInInspector]
    public int index = 0;

    //초기화
    void Awake()
    {
        text = transform.FindChild("Text").GetComponent<Text>();
    }
    
    //드롭다운 켜기 / 끄기
    public void PressBtn()
    {
        if (scroll_view.activeSelf)
        {
            scroll_view.SetActive(false);
            active = false;
        }
        else
        {
            scroll_view.SetActive(true);
            active = true;
        }
    }

    //버튼 변경
    public void ChangeBtn(string _text, int _index)
    {
        index = _index;
        text.text = _text;
    }

    //텍스트 받기
    public string GetText()
    {
        return text.text;
    }
}
