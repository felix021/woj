<?

//��ʽ��html���,Ҫ�����ʽ�����ı���������Ƕ�׵ı�ǩ
//�Ҵ���ʽ���ı��в�������<pre>��ǩ
class format_output
{

	var $input;
	var $output;

	function format_output($in)
	{
		$this->input = $in;
	}

	function set_input($in)
	{
		$this->input = $in;
	}

	//ת����ͨ�ı��е������ַ�,��Ҫת���������ַ���:
	//<         &#60;
	//>         &#62;
	//&         &#38;
	//'         &#39;
	//"         &#34;
	//\n        <br>
	//' '       &nbsp;

	//����ʶ���html��ǩ��:
	//<font> <img>
	$htmlTag={"font","img"};
	function change($ch)
	{
		if($ch != "<" && $ch != ">" && $ch != "&" &&
			$ch != "\'" && $ch != "\"" && $ch != "\n" && $ch != " "){
			return $ch;
		}

		switch($ch){
			case "<": $str = "&#60;";  break;
			case ">": $str = "&#62;";  break;
			case "&": $str = "&#38;";  break;
			case "\'": $str = "&#39;"; break;
			case "\"": $str = "&#34;"; break;
			case "\n": $str = "<br>"; break;
			case " ": $str = "&nbsp;"; break;
		}
		return $str;
	}

	function isTag($str){
		for($i = 0;i < count(htmlTag);$i++){
			if(htmlTag[$i] == str) return true;
		}
		return false;
	}

	function formatHtml($origHtml)
	{
		$formatHtml = '';
		$status = 0;   //״̬��ת��,0��ʾԭʼ״̬
		$index = 0;
		$len = strlen($origHtml);
		$next = 0;
		$match = '';
		while($index < $len){
			$ch = origHtml{$index};
			if($status == 0){		//ԭʼ״̬(״̬0)
				if($ch != '<'){
					$formatHtml.=(change($ch));
					$index++;
				}
				else{
					$next = $index+1;
					while($next < $len && ($ch=origHtml{$next}) != ' '
					    && $ch != "\n" && $ch != '>') $next++;
					$sub = substr($origHtml,$index+1,$next);
					if(!isTag($sub)){
						$formatHtml.=change($origHtml{index});
						$index++;
					}
					else{
						$formatHtml.=("<".$sub);
						$match = $sub;
						$index = $next;
						$status = 1;
					}
				}
			}
			else if($status == 1){ //ʶ���˰����ǩ״̬(״̬1)
				while($index < $len && $ch != '>' && $ch != '/'){
					$formatHtml.=$ch;
					$index++;
					$ch = $origHtml($index};

				}
				if($ch == '>'){
					$formatHtml.=$ch;
					$index++;
					$status = 2;
				}
				else{
					if($index < $len && $origHtml{$index+1} == '>'){
						$formatHtml.=("/>");
						$index+=2;
						$status = 0;
					}
					else{
						$formatHtml.=$ch;
						$index++;
					}
				}
			}
			else{					//ʶ�������ǩ(״̬2)
				while($index < $len && $ch != '<'){
					$formatHtml.=(change($ch));
					$index++;
					$ch = $origHtml{$index};
				}
				if($ch == '<' && $index < $len && $origHtml{$index+1} == '/'){
					$next = $index+2;
					while($next < $len && ($ch=$origHtml{$next}) != ' '
					    && $ch != "\n" && $ch != ">") $next++;
					$sub = substr($origHtml, $index+2,$next);
					if($match == $sub && $origHtml{$next} == '>'){
						$formatHtml.=("</".$sub.">");
						$status = 0;
						$index = $next + 1;
					}
					else{
						$formatHtml.=(change($origHtml{index}));
						$index++;
					}
				}
				else{
					$formatHtml.=(change($ch));
					$index++;
				}
			}
		}
		return $formatHtml;
	}


}
?>