<?php

/**
 * @brief Find incoming properties to a result set
 *
 * @since 1.8
 *
 * @author mwjames
 *
 * @ingroup SemanticResultFormats
 * @file SRF_Incoming.php
 */
class SRFIncoming extends SMWResultPrinter {
	/**
	 * Get a human readable label for this printer.
	 *
	 * @return string
	 */
	public function getName() {
		return wfMessage( 'srf-printername-incoming' )->text();
	}

	/**
	 * Returns an array with an enhanced data set retrieved from the query result.
	 *
	 * @since 1.8
	 *
	 * @param SMWQueryResult $result
	 * @param $outputMode
	 *
	 * @return string
	 */
	protected function getResultText( SMWQueryResult $result, $outputmode ) {
		$data = $this->getResultData( $result, $outputmode );

		// Bailout if we have no results
		if ( $data === [] ){
			if ( $this->params['default'] !== '' ) {
				return $this->escapeText( $this->params['default'], $outputmode );
			} else {
				$result->addErrors( [ wfMessage( 'smw_result_noresults' )->inContentLanguage()->text() ] );
				return '';
			}
		} else {
			return $this->getFormatOutput( $data );
		}
	}

	/**
	 * Return relevant data set
	 *
	 * @param SMWQueryResult $res
	 * @param $outputMode
	 *
	 * @return array
	 */
	protected function getResultData( SMWQueryResult $res, $outputMode ) {
		// Init
		$properties = [];
		$excludeProperties = explode( $this->params['sep'], $this->params['exclude'] );

		// Options used when retrieving data from the store
		$reqOptions        = new SMWRequestOptions();
		$reqOptions->sort  = true;
		$reqOptions->limit = $this->params['limit'];

		foreach ( $res->getResults() as $key => $page ) {

			// Find properties assigned to selected subject page
			foreach( $res->getStore()->getInProperties( $page, $reqOptions ) as $property ) {

				$propertyLabel = $property->getLabel();

				// Exclude property from result set
				if ( in_array( $propertyLabel, $excludeProperties ) ) {
					continue;
				}

				// NOTE There could be thousands of incoming links for one property,
				// counting the length of an array is inefficient but we don't want
				// to implement any native db select outside of smw core and rather
				// would like to see a counting method available but to counter
				// any potential inefficiencies we curb the selection by using
				// SMWRequestOptions -> limit as boundary
				$count = count ( $res->getStore()->getPropertySubjects( $property, $page, $reqOptions ) );

				// Limit ouput by threshold
				if ( $this->params['min'] <= $count ) {
					$properties[$propertyLabel] = $count;
				}
			}
		}

		return $properties;
	}

	/**
	 * Create a template output
	 *
	 * @since 1.8
	 *
	 * @param $label
	 * @param $count
	 * @param $rownum
	 * @param $result
	 */
	protected function initTemplateOutput( $label, $count, &$rownum, &$result ) {
		$rownum++;
		$wikitext  = $this->params['userparam'] ? "|userparam=" . $this->params['userparam'] : '';
		$wikitext .= $this->params['count'] ? "|count=" . $count : '';
		$wikitext .= $this->params['sep'] ? "|sep=" . $this->params['sep'] : '';
		$wikitext .= "|" . $label;
		$wikitext .= "|#=$rownum";
		$result   .= '{{' . trim ( $this->params['template'] ) . $wikitext . '}}';
	}

	/**
	 * Prepare data for the output
	 *
	 * @since 1.8
	 *
	 * @param array $data
	 */
	protected function getFormatOutput( array $data ) {
		// Init
		$result = '';

		// Build the template output
		if ( $this->params['template'] !== '' ) {
			$this->hasTemplates = true;
			foreach ( $data as $propLabel => $propCount ) {
				$this->initTemplateOutput ( $propLabel , $propCount, $rownum, $result);
			}
		} else {
			// Plain list output
			$result = implode( $this->params['sep'] , array_keys( $data ) ) ;
		}

		// Beautify class selector
		$class = $this->params['template'] ? 'srf-incoming ' .  str_replace( " ","-", $this->params['template'] ) : 'srf-incoming';

		// Output
		return Html::rawElement(
			'div',
			 [ 'class' => $class ],
			$result
		);
	}

	/**
	 * @see SMWResultPrinter::getParamDefinitions
	 *
	 * @since 1.8
	 *
	 * @param $definitions array of IParamDefinition
	 *
	 * @return array of IParamDefinition|array
	 */
	public function getParamDefinitions( array $definitions ) {
		$params = parent::getParamDefinitions( $definitions );

		$params['sep'] = [
			'message' => 'smw-paramdesc-sep',
			'default' => ',',
		];

		$params['exclude'] = [
			'message' => 'srf-paramdesc-excludeproperty',
			'default' => '',
		];

		$params['min'] = [
			'message' => 'srf-paramdesc-min',
			'default' => '',
		];

		$params['template'] = [
			'message' => 'smw-paramdesc-template',
			'default' => '',
		];

		$params['userparam'] = [
			'message' => 'smw-paramdesc-userparam',
			'default' => '',
		];

		$params['count'] = [
			'type' => 'boolean',
			'message' => 'srf-paramdesc-count',
			'default' => '',
		];

		return $params;
	}
}
